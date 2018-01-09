<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\ViewBuilder\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Blast\Component\ViewBuilder\Builder\ViewBuilder;
use Blast\Component\ViewBuilder\Factory\BaseAbstractFactory;

class BuilderTest extends TestCase
{
    public function testBuilder()
    {
        $buider = new ViewBuilder(new BaseAbstractFactory());
        $form = $buider->form('category');
        $form
          ->tab('general')
            ->group('')

              ->field('name')
                ->required()
              ->end()

              ->field('treeParent')
                ->type(NestedTreeableType::class)
                ->entityClass(Category::class)
                ->required(false)
                ->placeholder('')
              ->end()

            ->end()
        ->end();
    }

    public function testBuilder2()
    {
        $view = new ViewBuilder(new BaseAbstractFactory());
        $view

          ->tab('general')
            ->template('@MyBundle:Contact/Info.html.twig')
          ->end()

          ->tab('organizations')
            ->import('view.organizations')
          ->end()

          ->tab('coordonnées')

            ->group('adresses')
              ->css(['col-md-6'])
              ->template('@MyBundle:Address/CardView.html.twig')
              ->data()
                ->modelClass('My\Address')
                ->type('collection')
                ->path('addresses')
              ->end()
            ->end()

            ->group('téléphones')
              ->css(['col-md-6'])
              ->template('@MyBundle:Phone/CardView.html.twig')
              ->data()
                ->modelClass('My\Phone')
                ->type('collection')
                ->repository()
                  ->method('findPhonesByContact')
                  ->arguments(['&'])
                ->end()
              ->end()
            ->end()

          ->end()

        ->end();
    }
}
