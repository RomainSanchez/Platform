<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\ViewBuilder\Model;

class Tab extends AbstractModel
{

  protected $label;
  protected $order = 0;
  protected $groups = [];

  public function getLabel()
  {
    return $this->label;
  }

  public function setLabel(string $label)
  {
    $this->label = $label;
  }

  public function getOrder()
  {
    return $this->order;
  }

  public function setOrder(int $order)
  {
    $this->order = $order;
  }

  public function addGroup(ModelInterface $group)
  {
    $this->groups[] = $group;
  }
}
