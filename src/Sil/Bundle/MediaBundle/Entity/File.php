<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\MediaBundle\Entity;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\BaseEntity;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Jsonable;

/**
 * File.
 */
class File implements \JsonSerializable, FileInterface
{
    use BaseEntity,
        Jsonable;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $mimeType;

    /**
     * @var float
     */
    protected $size;

    /**
     * @var UploadedFile file
     */
    protected $file;

    /**
     * @var object
     */
    protected $parent;

    /**
     * @var bool
     */
    protected $owned;

    public function __construct()
    {
    }

    /**
     * @param UploadedFile $file
     *
     * @return FileInterface
     */
    public function setFile($file = null)
    {
        if ($file instanceof UploadedFile) {
            $this->file = base64_encode(file_get_contents($file));
        } else {
            $this->file = $file;
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return base64_decode($this->file);
    }

    /**
     * @return string
     */
    public function getBase64File()
    {
        return $this->file;
    }

    /**
     * Set parent.
     *
     * @param string $parent
     *
     * @return FileInterface
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent.
     *
     * @return string
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return FileInterface
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
     * Set mimeType.
     *
     * @param string $mimeType
     *
     * @return FileInterface
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * Get mimeType.
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Set size.
     *
     * @param float $size
     *
     * @return FileInterface
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size.
     *
     * @return float
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set owned.
     *
     * @param string $owned
     *
     * @return FileInterface
     */
    public function setOwned($owned)
    {
        $this->owned = $owned;

        return $this;
    }

    /**
     * Get owned.
     *
     * @return string
     */
    public function getOwned()
    {
        return $this->owned;
    }

    /**
     * isOwned.
     *
     * @return bool
     */
    public function isOwned(): bool
    {
        return (bool) $this->owned;
    }

    public function __clone()
    {
        $this->id = null;
        $this->owned = false;
    }
}
