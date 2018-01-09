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

abstract class AbstractBuilder implements BuilderInterface
{
    /**
     * @var ModelInterface
     */
    protected $model;

    /**
     * @var BuilderInterface
     */
    protected $parent;

    /**
     * @var AbstractFactoryInterface
     */
    protected $abstractFactory;

    /**
     * @var array
     */
    protected $options = [];

    public function __construct(BuilderInterface $parent = null, AbstractFactoryInterface $abstractFactory)
    {
        $this->parent = $parent;
        $this->abstractFactory = $abstractFactory;
    }

    /**
     * @return AbstractFactoryInterface
     */
    public function getAbstractFactory()
    {
        return $this->abstractFactory;
    }

    /**
     * @return BuilderInterface
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return ModelInterface
     */
    public function getParentModel()
    {
        return $this->getParent()->getModel();
    }

    /**
     * @return ModelInterface
     */
    public function getModel()
    {
      if(null === $this->model){
        $this->model = $this->build();
      }
        return $this->model;
    }

    /**
     * @return BuilderInterface
     */
    public function end()
    {
        return $this->getParent();
    }

    protected abstract function build();
}
