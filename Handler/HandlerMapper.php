<?php

/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Infrastructure\CommandBusBundle\Handler;


use Infrastructure\CommandBusBundle\Exception\HandlerNotFoundException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

class HandlerMapper
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
     * @param $messageName
     *
     * @return callable
     * @throws HandlerNotFoundException|ServiceNotFoundException|ServiceCircularReferenceException
     */
    public function get($messageName)
    {

        if (!array_key_exists($messageName, $this->callableMap)) {
            throw new HandlerNotFoundException(sprintf(
                'Could not find a handler for name "%s"',
                $messageName
            ));
        }

        $callable = $this->callableMap[$messageName];

        $methodName = $callable['method'] ?: 'execute';

        return [
            $this->container->get($callable['service']),
            $methodName
        ];

    }


}