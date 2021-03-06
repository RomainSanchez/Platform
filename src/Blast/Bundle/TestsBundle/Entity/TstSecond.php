<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\TestsBundle\Entity;

/**
 * TstSecond.
 */
class TstSecond
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $parent;

    /**
     * @var \Blast\Bundle\TestsBundle\Entity\TstSimple
     */
    private $simple;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->parent = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return TstSecond
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add parent.
     *
     * @param \Blast\Bundle\TestsBundle\Entity\TstParent $parent
     *
     * @return TstSecond
     */
    public function addParent(\Blast\Bundle\TestsBundle\Entity\TstParent $parent)
    {
        $this->parent[] = $parent;

        return $this;
    }

    /**
     * Remove parent.
     *
     * @param \Blast\Bundle\TestsBundle\Entity\TstParent $parent
     */
    public function removeParent(\Blast\Bundle\TestsBundle\Entity\TstParent $parent)
    {
        $this->parent->removeElement($parent);
    }

    /**
     * Get parent.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set simple.
     *
     * @param \Blast\Bundle\TestsBundle\Entity\TstSimple $simple
     *
     * @return TstSecond
     */
    public function setSimple(\Blast\Bundle\TestsBundle\Entity\TstSimple $simple = null)
    {
        $this->simple = $simple;

        return $this;
    }

    /**
     * Get simple.
     *
     * @return \Blast\Bundle\TestsBundle\Entity\TstSimple
     */
    public function getSimple()
    {
        return $this->simple;
    }

    /**
     * @var string
     */
    private $code;

    /**
     * Set code.
     *
     * @param string $code
     *
     * @return TstSecond
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @var int
     */
    private $id;

    /**
     * Set id.
     *
     * @param int $id
     *
     * @return TstSecond
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
