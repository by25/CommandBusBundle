<?php

/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Infrastructure\CommandBusBundle\Command;


use Infrastructure\CommandBusBundle\Middleware\MiddlewareInterface;
use Infrastructure\CommandBusBundle\Handler\HandlerMapper;

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
     * @param CommandInterface $command
     * @throws \Infrastructure\CommandBusBundle\Exception\HandlerNotFoundException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    public function handle(CommandInterface $command)
    {
        foreach ($this->middleware as $middleware) {
            $middleware->handle($command);
        }

        $handler = $this->handlerMapper->get($command->messageName());
        call_user_func($handler, $command);
    }


}