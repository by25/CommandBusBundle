<?php

/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\CommandBusBundle\CommandBus;

use Itmedia\CommandBusBundle\Command\Command;
use Itmedia\CommandBusBundle\Middleware\MiddlewareInterface;
use Itmedia\CommandBusBundle\Handler\HandlerMapper;

class CommandBus implements CommandBusInterface
{

    /**
     * @var HandlerMapper
     */
    private $handlerMapper;

    /**
     * @var MiddlewareInterface[]
     */
    private $middleware = [];


    /**
     * CommandBus constructor.
     *
     * @param HandlerMapper $handlerMapper
     */
    public function __construct(HandlerMapper $handlerMapper)
    {
        $this->handlerMapper = $handlerMapper;
    }


    /**
     * {@inheritdoc}
     */
    public function addMiddleware(MiddlewareInterface $middleware)
    {
        $this->middleware[] = $middleware;
    }


    /**
     * {@inheritdoc}
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
