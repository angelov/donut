<?php

/**
 * Donut Social Network - Yet another experimental social network.
 * Copyright (C) 2016-2018, Dejan Angelov <angelovdejan92@gmail.com>
 *
 * This file is part of Donut Social Network.
 *
 * Donut Social Network is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Donut Social Network is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Donut Social Network.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package Donut Social Network
 * @copyright Copyright (C) 2016-2018, Dejan Angelov <angelovdejan92@gmail.com>
 * @license https://github.com/angelov/donut/blob/master/LICENSE
 * @author Dejan Angelov <angelovdejan92@gmail.com>
 */

namespace Angelov\Donut\Tests\Donut\Core\CommandBus;

use Angelov\Donut\Core\CommandBus\AutoconfigureCommandHandlersCompilerPass;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class AutoconfigureCommandHandlersCompilerPassTest extends AbstractCompilerPassTestCase
{
    protected function registerCompilerPass(ContainerBuilder $container) : void
    {
        $container->addCompilerPass(new AutoconfigureCommandHandlersCompilerPass());
    }

    /**
     * @test
     * @dataProvider listCommandHandlers
     */
    public function it_adds_proper_tag_to_command_handlers(string $handlerClass, ?string $commandClass) : void
    {
        $this->setDefinition($handlerClass, new Definition());

        $this->compile();

        $service = $this->container->getDefinition($handlerClass);

        $this->assertTrue($service->isPublic());
        $this->assertArrayHasKey('command_handler', $service->getTags());
        $this->assertArraySubset(['handles' => $commandClass], $service->getTag('command_handler')[0]);
    }

    public function listCommandHandlers() : array
    {
        return [
            ['Angelov\\Something\\Handlers\\GrantTheWishCommandHandler', 'Angelov\\Something\\Commands\\GrantTheWishCommand'],
            ['Angelov\\Handlers\\DoSomethingCommandHandler', 'Angelov\\Commands\\DoSomethingCommand']
        ];
    }

    /**
     * @test
     * @dataProvider listOtherServices
     */
    public function it_ignores_other_services(string $serviceClass, bool $isPublic) : void
    {
        $this->setDefinition($serviceClass, (new Definition())->setPublic($isPublic));

        $this->compile();

        $service = $this->container->getDefinition($serviceClass);

        $this->assertSame($service->isPublic(), $isPublic);
        $this->assertArrayNotHasKey('command_handler', $service->getTags());
    }

    public function listOtherServices() : array
    {
        return [
            ['Angelov\\DoSomethingCommandHandler', true],
            ['Angelov\\Handlers\\SomeService', false],
            ['Angelov\\Users\\DoctrineUsersRepository', false]
        ];
    }
}
