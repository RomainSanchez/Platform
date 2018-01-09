<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\ViewBuilder\Factory;

use Blast\Component\ViewBuilder\Model;

class BaseAbstractFactory implements AbstractFactoryInterface
{
    public function createTab($options)
    {
        return new Model\Tab();
    }

    public function createGroup($options)
    {
        return new Model\Group();
    }

    public function createField($options)
    {
        return new Model\Field();
    }

    public function createForm($options)
    {
        return new Model\Form();
    }
}
