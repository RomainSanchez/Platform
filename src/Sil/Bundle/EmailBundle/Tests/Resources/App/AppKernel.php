<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            // Librinfo
            new Sil\Bundle\EmailBundle\SilEmailBundle(),
            new Sil\Bundle\MediaBundle\SilMediaBundle(),
            // Sonata
            new Sonata\CoreBundle\SonataCoreBundle(),
            new Sonata\BlockBundle\SonataBlockBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
            new Sonata\AdminBundle\SonataAdminBundle(),
            // new Sonata\IntlBundle\SonataIntlBundle(),
            // Blast
            new Blast\Bundle\OuterExtensionBundle\BlastOuterExtensionBundle(),
            new Blast\Bundle\CoreBundle\BlastCoreBundle(),
            new Blast\Bundle\BaseEntitiesBundle\BlastBaseEntitiesBundle(),
            new Blast\Bundle\UtilsBundle\BlastUtilsBundle(),
            // Wisiwig editor
            new Stfalcon\Bundle\TinymceBundle\StfalconTinymceBundle(),
        ];

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir() . '/config/config_' . $this->getEnvironment() . '.yml');
    }

    public function getCacheDir()
    {
        return sys_get_temp_dir() . '/EmailBundle/cache/' . $this->getEnvironment();
    }

    public function getLogDir()
    {
        return sys_get_temp_dir() . '/EmailBundle/logs';
    }
}
