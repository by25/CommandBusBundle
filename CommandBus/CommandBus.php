<?php

/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\CommandBusBundle\CommandBus;

use Itmedia\CommandBusBundle\Command\Command;
use Itmedia\CommandBusBundle\Handler\CommandHandlerMapper;
use Itmedia\CommandBusBundle\Handler\ContainerCommandHandlerMapper;
use Itmedia\CommandBusBundle\Middleware\Middleware;

class CommandBus
{
    private CommandHandlerMapper $handlerMapper;

    /**
     * @var Middleware[]
     */
    private array $middleware = [];

    public function __construct(CommandHandlerMapper $handlerMapper)
    {
        $this->handlerMapper = $handlerMapper;
    }


    /**
     * Добавить доп. обработчик команды (Middleware)
     */
    public function addMiddleware(Middleware $middleware)
    {
        $this->middleware[] = $middleware;
    }


    /**
     * Handle command
     *
     * @param Command $command
     * @throws \Itmedia\CommandBusBundle\Exception\HandlerNotFoundException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    public function handle(Command $command)
    {
        foreach ($this->middleware as $middleware) {
            $middleware->handle($command);
        }

        $handler = $this->handlerMapper->get($command->commandName());
        call_user_func($handler, $command);
    }
}
