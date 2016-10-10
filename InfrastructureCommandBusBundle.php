<?php

namespace Infrastructure\CommandBusBundle;

use Infrastructure\CommandBusBundle\DependencyInjection\Compiler\RegisterCommandHandlersCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class InfrastructureCommandBusBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterCommandHandlersCompilerPass(
            'infrastructure_command_bus.handler_mapper',
            'command_handler',
            'command'
        ));


//        $container->addCompilerPass(new RegisterCommandHandlersCompilerPass(
//            'infrastructure_command_bus.query_handler_mapper',
//            'query_handler',
//            'query'
//        ));

    }

}
