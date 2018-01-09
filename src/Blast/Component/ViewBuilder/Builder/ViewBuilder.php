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

use  Blast\Component\ViewBuilder\Factory\AbstractFactoryInterface;

class ViewBuilder extends AbstractBuilder
{
    public function __construct(AbstractFactoryInterface $abstractFactory)
    {
        parent::__construct(null, $abstractFactory);
    }

    public function form($name)
    {
        return new FormBuilder($this->getAbstractFactory(), $name);
    }

    protected function build(){
      return $this;
    }
}
