<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace  Blast\Bundle\DoctrineSessionBundle\Tests\Functional;

use Blast\Bundle\TestsBundle\Functional\BlastTestCase;

class DoctrineSessionServiceTest extends BlastTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testServicesAreInitializable()
    {
        $this->isServicesAreInitializable('blast_doctrine_session');
    }
}
