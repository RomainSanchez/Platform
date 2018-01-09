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

class FieldBuilder extends AbstractBuilder
{
    public function required($bool = true)
    {
        return $this;
    }

    public function type($type)
    {
        $this->options['type'] = $type;

        return $this;
    }

    public function entityClass($class)
    {
        $this->options['entityClass'] = $class;

        return $this;
    }

    public function placeholder($placeholder)
    {
        $this->options['placeholder'] = $placeholder;

        return $this;
    }

    protected function build()
    {
        return $this->getAbstractFactory()->createField($this->options);
    }

    public function end()
    {
        $this->getParentModel()->addField($this->getModel());
        return parent::end();
    }
}
