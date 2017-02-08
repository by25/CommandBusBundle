<?php

/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\CommandBusBundle\CommandBus;

use Itmedia\CommandBusBundle\Command\Command;
use Itmedia\CommandBusBundle\Middleware\MiddlewareInterface;

interface CommandBusInterface
{
    /**
     * Выполнить команду
     *
     * @param Command $command
     */
    public function handle(Command $command);

    /**
     * Добавить доп. обработчик команды (Middleware)
     *
     * @param MiddlewareInterface $middleware
     */
    public function addMiddleware(MiddlewareInterface $middleware);
}
