<?php

/*
 * This file is part of the Sil Project.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Entity;

use Sylius\Component\Product\Model\ProductAttribute as BaseProductAttribute;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Stringable;

class ProductAttribute extends BaseProductAttribute
{
    use Stringable;
}
