<?php

/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\CommandBusBundle\Handler;

use Itmedia\CommandBusBundle\Exception\HandlerNotFoundException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

class ContainerCommandHandlerMapper implements CommandHandlerMapper
{
    /**
     * Карта методов вызова
     * [messageName => [service => '...', 'method' => '...']]
     *
     * @var array
     */
    private $callableMap = [];

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * HandlerMapper constructor.
     *
     * @param ContainerInterface $container
     * @param array $callableMap
     */
    public function __construct(ContainerInterface $container, array $callableMap)
    {
        $this->container = $container;
        $this->callableMap = $callableMap;
    }


    /**
     * @param $commandName
     *
     * @return callable
     * @throws HandlerNotFoundException|ServiceNotFoundException|ServiceCircularReferenceException
     */
    public function get($commandName)
    {
        if (!array_key_exists($commandName, $this->callableMap)) {
            throw new HandlerNotFoundException(sprintf(
                'Could not find a handler for name "%s"',
                $commandName
            ));
        }

        $callable = $this->callableMap[$commandName];

        $methodName = $callable['method'] ?: 'execute';

        return [
            $this->container->get($callable['service']),
            $methodName
        ];
    }
}
