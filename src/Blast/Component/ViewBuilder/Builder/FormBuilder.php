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

use Blast\Component\ViewBuilder\Factory\AbstractFactoryInterface;
use Blast\Component\ViewBuilder\Model\ModelInterface;

class FormBuilder extends AbstractBuilder
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $tabs = [];


    public function __construct(AbstractFactoryInterface $abstractFactory, string $name)
    {
        parent::__construct(null, $abstractFactory);
        $this->name = $name;
    }
    public function tab($name)
    {
        return new TabBuilder($this, $this->getAbstractFactory());
    }

    protected function build(){
      return $this->getAbstractFactory()->createForm($this->options);
    }
}
