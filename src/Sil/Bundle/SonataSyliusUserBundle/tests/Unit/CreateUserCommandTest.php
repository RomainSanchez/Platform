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

namespace Librinfo\SonataSyliusUserBundle\Command\Test\Unit;

use Librinfo\SonataSyliusUserBundle\Command\CreateUserCommand;
/* * Librinfo\SonataSyliusUserBundle\Command\CreateUserCommand'
 * Generated by PHPUnit_SkeletonGenerator on 2017-05-02 at 16:34:40.
 */
use PHPUnit\Framework\TestCase;

class CreateUserCommandTest extends TestCase
{
    /**
     * @var CreateUserCommand
     */
    protected $myCommand;
    protected $myConfig;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->myCommand = new CreateUserCommand();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers \Librinfo\SonataSyliusUserBundle\Command\CreateUserCommand::configure
     *
     * @todo   Implement testConfigure().
     */
    public function testConfigure()
    {
        $this->markTestIncomplete('This test has not been implemented yet.');

        $this->myCommand->configure();

        $this->myConfig = $this->myCommand->getName();
        /*
         * @todo : check if we need to test more (or not)
         */
        $this->assertContains('user', $this->myConfig);
    }

    public function testExecute()
    {
        /*
         * @todo : Is it really relevant to perform tests on it?
         */
    }
}
