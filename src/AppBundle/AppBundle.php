<?php

namespace AppBundle;

use SocNet\Core\CommandBus\TacticianAdapter\HandlersTagsCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AppBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new HandlersTagsCompilerPass());
    }
}
