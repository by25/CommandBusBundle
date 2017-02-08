<?php

namespace Itmedia\CommandBusBundle;

use Itmedia\CommandBusBundle\DependencyInjection\Compiler\RegisterCommandHandlersCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ItmediaCommandBusBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterCommandHandlersCompilerPass(
            'itmedia_command_bus.container_handler_mapper',
            'command_handler',
            'command'
        ));
    }
}
