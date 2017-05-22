<?php

namespace SocNet\Core\CommandBus\TacticianAdapter;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class HandlersTagsCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $handlerIds = $container->findTaggedServiceIds('command_handler');

        foreach ($handlerIds as $id => $tags) {

            $definition = $container->getDefinition($id);
            $command = $definition->getTag('command_handler')[0]['handles'];

            $definition->addTag('tactician.handler', [
                'command' => $command
            ]);

        }
    }
}
