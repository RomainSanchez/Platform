<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\ViewBuilder\Builder;

class GroupBuilder extends AbstractBuilder
{
    public function field($name)
    {
        return new FieldBuilder($this, $this->getAbstractFactory(), $name);
    }

    public function build()
    {
        return $this->getAbstractFactory()->createGroup($this->options);
    }

    public function end()
    {
        $this->getParentModel()->addGroup($this->getModel());
        print_r($this->getParentModel());
        return parent::end();
    }
}
