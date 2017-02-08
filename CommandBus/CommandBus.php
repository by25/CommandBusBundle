<?php

/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\CommandBusBundle\CommandBus;

use Itmedia\CommandBusBundle\Command\Command;
use Itmedia\CommandBusBundle\Handler\ContainerCommandHandlerMapper;
use Itmedia\CommandBusBundle\Middleware\Middleware;

class CommandBus
{

    /**
     * @var ContainerCommandHandlerMapper
     */
    private $handlerMapper;

    /**
     * @var Middleware[]
     */
    private $middleware = [];


    /**
     * CommandBus constructor.
     *
     * @param ContainerCommandHandlerMapper $handlerMapper
     */
    public function __construct(ContainerCommandHandlerMapper $handlerMapper)
    {
        $this->handlerMapper = $handlerMapper;
    }


    /**
     * Добавить доп. обработчик команды (Middleware)
     *
     * @param Middleware $middleware
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
