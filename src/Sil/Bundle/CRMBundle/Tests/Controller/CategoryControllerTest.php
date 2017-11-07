<?php

/*
 * This file is part of the Blast Project package.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Librinfo\CRMBundle\Tests\Controller;

use Librinfo\CRMBundle\Entity\Category;
use Librinfo\CRMBundle\Entity\Organism;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoryControllerTest extends WebTestCase
{
    private $datafixtures;
    private $client;

    public function init()
    {
        $this->client = static::createClient();

        $this->datafixtures = $this->client->getContainer()->getParameter('librinfo.crmbundle.datafixtures');
    }

    /**
     * testsAdd.
     */
    public function testsAdd()
    {
        $this->init();

        $category = new Category();

        $category->setName($this->datafixtures['category']['name']);

        $this->assertEquals($this->datafixtures['category']['name'], $category->getName());
    }

    /**
     * testsOrganism.
     */
    public function testsOrganism()
    {
        $this->init();

        $category = new Category();

        $organism = new Organism();
        $organism->setName($this->datafixtures['organism']['name']);

        $category->addOrganism($organism);
        $this->assertContains($organism, $category->getOrganisms());

        $category->removeOrganism($organism);
        $this->assertNotContains($organism, $category->getOrganisms());
    }
}
