<?php

namespace SocNet\Tests\Core\CommandBus\TacticianAdapter;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use SocNet\Core\CommandBus\TacticianAdapter\HandlersTagsCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class HandlersTagsCompilerPassTest extends AbstractCompilerPassTestCase
{
    protected function registerCompilerPass(ContainerBuilder $container)
    {
        $container->addCompilerPass(new HandlersTagsCompilerPass());
    }

    /** @test */
    public function it_adds_tactician_tag_to_command_handler_services()
    {
        $handler = new Definition();
        $handler->addTag('command_handler', ['handles' => 'example_command']);
        $this->setDefinition('example_command_handler', $handler);

        $secondHandler = new Definition();
        $secondHandler->addTag('command_handler', ['handles' => 'second_example_command']);
        $this->setDefinition('second_example_command_handler', $secondHandler);

        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithTag('example_command_handler', 'tactician.handler', [
            'command' => 'example_command'
        ]);

        $this->assertContainerBuilderHasServiceDefinitionWithTag('second_example_command_handler', 'tactician.handler', [
            'command' => 'second_example_command'
        ]);
    }
}
