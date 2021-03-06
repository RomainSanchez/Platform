<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\CoreBundle\Command;

use Sensio\Bundle\GeneratorBundle\Command\Helper\DialogHelper;
use Sensio\Bundle\GeneratorBundle\Command\Helper\QuestionHelper;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Sonata\AdminBundle\Model\ModelManagerInterface;
use Sonata\AdminBundle\Command\Validators;
use Blast\Bundle\CoreBundle\Generator\AdminGenerator;
use Blast\Bundle\CoreBundle\Generator\ControllerGenerator;
use Blast\Bundle\CoreBundle\Generator\ServicesManipulator;
use Blast\Bundle\CoreBundle\Generator\BlastGenerator;
use Blast\Bundle\CoreBundle\Command\Traits\Interaction;

/**
 * Class GenerateAdminCommand.
 */
class GenerateAdminCommand extends ContainerAwareCommand
{
    use Interaction;

    /**
     * @var string[]
     */
    private $managerTypes;

    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $this
            ->setName('blast:generate:admin')
            ->setDescription('Generates an admin class based on the given model class')
            ->addArgument('model', InputArgument::REQUIRED, 'The fully qualified model class')
            ->addOption('bundle', 'b', InputOption::VALUE_OPTIONAL, 'The bundle name')
            ->addOption('admin', 'a', InputOption::VALUE_OPTIONAL, 'The admin class basename')
            ->addOption('controller', 'c', InputOption::VALUE_OPTIONAL, 'The controller class basename')
            ->addOption('manager', 'm', InputOption::VALUE_OPTIONAL, 'The model manager type')
            ->addOption('services', 'y', InputOption::VALUE_OPTIONAL, 'The services YAML file', 'services.yml')
            ->addOption('id', 'i', InputOption::VALUE_OPTIONAL, 'The admin service ID')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled()
    {
        return class_exists('Sensio\\Bundle\\GeneratorBundle\\SensioGeneratorBundle');
    }

    /**
     * @param string $managerType
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public function validateManagerType($managerType)
    {
        $managerTypes = $this->getAvailableManagerTypes();

        if (!isset($managerTypes[$managerType])) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid manager type "%s". Available manager types are "%s".',
                $managerType,
                implode('", "', $managerTypes)
            ));
        }

        return $managerType;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $modelClass = Validators::validateClass($input->getArgument('model'));
        $modelClassBasename = current(array_slice(explode('\\', $modelClass), -1));
        $bundle = $this->getBundle($input->getOption('bundle') ?: $this->getBundleNameFromClass($modelClass));
        $adminClassBasename = $input->getOption('admin') ?: $modelClassBasename . 'Admin';
        $adminClassBasename = Validators::validateAdminClassBasename($adminClassBasename);
        $managerType = $input->getOption('manager') ?: $this->getDefaultManagerType();
        $modelManager = $this->getModelManager($managerType);
        $skeletonDirectory = __DIR__ . '/../Resources/skeleton';
        $adminGenerator = new AdminGenerator($modelManager, $skeletonDirectory);

        try {
            $adminGenerator->generate($bundle, $adminClassBasename, $modelClass);
            $output->writeln(sprintf(
                '%sThe admin class "<info>%s</info>" has been generated under the file "<info>%s</info>".',
                PHP_EOL,
                $adminGenerator->getClass(),
                realpath($adminGenerator->getFile())
            ));
        } catch (\Exception $e) {
            $this->writeError($output, $e->getMessage());
        }

        if ($controllerClassBasename = $input->getOption('controller')) {
            $controllerClassBasename = Validators::validateControllerClassBasename($controllerClassBasename);
            $controllerGenerator = new ControllerGenerator($skeletonDirectory);

            try {
                $controllerGenerator->generate($bundle, $controllerClassBasename);
                $output->writeln(sprintf(
                    '%sThe controller class "<info>%s</info>" has been generated under the file "<info>%s</info>".',
                    PHP_EOL,
                    $controllerGenerator->getClass(),
                    realpath($controllerGenerator->getFile())
                ));
            } catch (\Exception $e) {
                $this->writeError($output, $e->getMessage());
            }
        }

        if ($servicesFile = $input->getOption('services')) {
            $adminClass = $adminGenerator->getClass();
            $file = sprintf('%s/Resources/config/%s', $bundle->getPath(), $servicesFile);
            $servicesManipulator = new ServicesManipulator($file);
            $controllerName = $controllerClassBasename
                ? sprintf('%s:%s', $bundle->getName(), substr($controllerClassBasename, 0, -10))
                : 'BlastCoreBundle:CRUD'
            ;

            try {
                $id = $input->getOption('id') ?: $this->getAdminServiceId($bundle->getName(), $adminClassBasename);
                $servicesManipulator->addResource($id, $modelClass, $adminClass, $controllerName, $managerType);
                $output->writeln(sprintf(
                    '%sThe service "<info>%s</info>" has been appended to the file <info>"%s</info>".%s',
                    PHP_EOL,
                    $id,
                    realpath($file),
                    PHP_EOL
                ));
            } catch (\Exception $e) {
                $this->writeError($output, $e->getMessage());
            }
        }

        try {
            $blastFile = sprintf('%s/Resources/config/blast.yml', $bundle->getPath());
            $blastGenerator = new BlastGenerator($blastFile, $modelManager, $skeletonDirectory);
            $blastGenerator->addResource($modelClass);
        } catch (\Exception $e) {
            $this->writeError($output, $e->getMessage());
        }

        return 0;
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $questionHelper = $this->getQuestionHelper();
        $questionHelper->writeSection($output, 'Welcome to the Blast Sonata admin generator');
        $modelClass = $this->askAndValidate(
            $input,
            $output,
            'The fully qualified model class',
            $input->getArgument('model'),
            'Sonata\AdminBundle\Command\Validators::validateClass'
        );

        $modelClassBasename = current(array_slice(explode('\\', $modelClass), -1));
        $bundleName = $this->askAndValidate(
            $input,
            $output,
            'The bundle name',
            $input->getOption('bundle') ?: $this->getBundleNameFromClass($modelClass),
            'Sensio\Bundle\GeneratorBundle\Command\Validators::validateBundleName'
        );
        $adminClassBasename = $this->askAndValidate(
            $input,
            $output,
            'The admin class basename',
            $input->getOption('admin') ?: $modelClassBasename . 'Admin',
            'Sonata\AdminBundle\Command\Validators::validateAdminClassBasename'
        );

        if (count($this->getAvailableManagerTypes()) > 1) {
            $managerType = $this->askAndValidate(
                $input,
                $output,
                'The manager type',
                $input->getOption('manager') ?: $this->getDefaultManagerType(),
                array($this, 'validateManagerType')
            );
            $input->setOption('manager', $managerType);
        }

        if ($this->askConfirmation($input, $output, 'Do you want to generate a controller', 'no', '?')) {
            $controllerClassBasename = $this->askAndValidate(
                $input,
                $output,
                'The controller class basename',
                $input->getOption('controller') ?: $modelClassBasename . 'AdminController',
                'Sonata\AdminBundle\Command\Validators::validateControllerClassBasename'
            );
            $input->setOption('controller', $controllerClassBasename);
        }

        if ($this->askConfirmation($input, $output, 'Do you want to update the services YAML configuration file', 'yes', '?')) {
            $path = $this->getBundle($bundleName)->getPath() . '/Resources/config/';
            $servicesFile = $this->askAndValidate(
                $input,
                $output,
                'The services YAML configuration file',
                is_file($path . 'admin.yml') ? 'admin.yml' : 'services.yml',
                'Sonata\AdminBundle\Command\Validators::validateServicesFile'
            );
            $id = $this->askAndValidate(
                $input,
                $output,
                'The admin service ID',
                $this->getAdminServiceId($bundleName, $adminClassBasename),
                'Sonata\AdminBundle\Command\Validators::validateServiceId'
            );
            $input->setOption('services', $servicesFile);
            $input->setOption('id', $id);
        }

        $input->setArgument('model', $modelClass);
        $input->setOption('admin', $adminClassBasename);
        $input->setOption('bundle', $bundleName);
    }

    /**
     * @param string $class
     *
     * @return string|null
     *
     * @throws \InvalidArgumentException
     */
    private function getBundleNameFromClass($class)
    {
        $application = $this->getApplication();
        /* @var $application Application */

        foreach ($application->getKernel()->getBundles() as $bundle) {
            if (strpos($class, $bundle->getNamespace() . '\\') === 0) {
                return $bundle->getName();
            }
        }

        return;
    }

    /**
     * @param string $name
     *
     * @return BundleInterface
     */
    private function getBundle($name)
    {
        return $this->getKernel()->getBundle($name);
    }

    /**
     * @param OutputInterface $output
     * @param string          $message
     */
    private function writeError(OutputInterface $output, $message)
    {
        $output->writeln(sprintf("\n<error>%s</error>", $message));
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param string          $questionText
     * @param mixed           $default
     * @param callable        $validator
     *
     * @return mixed
     */
    private function askAndValidate(InputInterface $input, OutputInterface $output, $questionText, $default, $validator)
    {
        $questionHelper = $this->getQuestionHelper();

        // NEXT_MAJOR: Remove this BC code for SensioGeneratorBundle 2.3/2.4 after dropping support for Symfony 2.3
        if ($questionHelper instanceof DialogHelper) {
            return $questionHelper->askAndValidate($output, $questionHelper->getQuestion($questionText, $default), $validator, false, $default);
        }

        $question = new Question($questionHelper->getQuestion($questionText, $default), $default);

        $question->setValidator($validator);

        return $questionHelper->ask($input, $output, $question);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param string          $questionText
     * @param string          $default
     * @param string          $separator
     *
     * @return string
     */
    private function askConfirmation(InputInterface $input, OutputInterface $output, $questionText, $default, $separator)
    {
        $questionHelper = $this->getQuestionHelper();

        // NEXT_MAJOR: Remove this BC code for SensioGeneratorBundle 2.3/2.4 after dropping support for Symfony 2.3
        if ($questionHelper instanceof DialogHelper) {
            $question = $questionHelper->getQuestion($questionText, $default, $separator);

            return $questionHelper->askConfirmation($output, $question, ($default === 'no' ? false : true));
        }

        $question = new ConfirmationQuestion($questionHelper->getQuestion(
            $questionText,
            $default,
            $separator
        ), ($default === 'no' ? false : true));

        return $questionHelper->ask($input, $output, $question);
    }

    /**
     * @return string
     *
     * @throws \RuntimeException
     */
    private function getDefaultManagerType()
    {
        if (!$managerTypes = $this->getAvailableManagerTypes()) {
            throw new \RuntimeException('There are no model managers registered.');
        }

        return current($managerTypes);
    }

    /**
     * @param string $managerType
     *
     * @return ModelManagerInterface
     */
    private function getModelManager($managerType)
    {
        return $this->getContainer()->get('sonata.admin.manager.' . $managerType);
    }

    /**
     * @param string $bundleName
     * @param string $adminClassBasename
     *
     * @return string
     */
    private function getAdminServiceId($bundleName, $adminClassBasename)
    {
        $prefix = substr($bundleName, -6) == 'Bundle' ? substr($bundleName, 0, -6) : $bundleName;
        $suffix = substr($adminClassBasename, -5) == 'Admin' ? substr($adminClassBasename, 0, -5) : $adminClassBasename;
        $suffix = str_replace('\\', '.', $suffix);

        return Container::underscore(sprintf(
            '%s.admin.%s',
            $prefix,
            $suffix
        ));
    }

    /**
     * @return string[]
     */
    private function getAvailableManagerTypes()
    {
        $container = $this->getContainer();

        if (!$container instanceof Container) {
            return array();
        }

        if ($this->managerTypes === null) {
            $this->managerTypes = array();

            foreach ($container->getServiceIds() as $id) {
                if (strpos($id, 'sonata.admin.manager.') === 0) {
                    $managerType = substr($id, 21);
                    $this->managerTypes[$managerType] = $managerType;
                }
            }
        }

        return $this->managerTypes;
    }

    /**
     * @return KernelInterface
     */
    private function getKernel()
    {
        /* @var $application Application */
        $application = $this->getApplication();

        return $application->getKernel();
    }

    /**
     * @return QuestionHelper|DialogHelper
     */
    private function getQuestionHelper()
    {
        // NEXT_MAJOR: Remove this BC code for SensioGeneratorBundle 2.3/2.4 after dropping support for Symfony 2.3
        if (class_exists('Sensio\Bundle\GeneratorBundle\Command\Helper\DialogHelper')) {
            $questionHelper = $this->getHelper('dialog');

            if (!$questionHelper instanceof DialogHelper) {
                $questionHelper = new DialogHelper();
                $this->getHelperSet()->set($questionHelper);
            }
        } else {
            $questionHelper = $this->getHelper('question');

            if (!$questionHelper instanceof QuestionHelper) {
                $questionHelper = new QuestionHelper();
                $this->getHelperSet()->set($questionHelper);
            }
        }

        return $questionHelper;
    }
}
