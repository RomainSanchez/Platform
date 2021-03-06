<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\BaseEntitiesBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Blast\Bundle\BaseEntitiesBundle\DependencyInjection\Compiler\ValidateExtensionConfigurationPass;

class BlastBaseEntitiesBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ValidateExtensionConfigurationPass());
    }
}
