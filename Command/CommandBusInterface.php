<?php

/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Infrastructure\CommandBusBundle\Command;

use Infrastructure\CommandBusBundle\Middleware\MiddlewareInterface;

interface CommandBusInterface
{
    /**
     * Выполнить команду
     *
     * @param CommandInterface $command
     */
    public function handle(CommandInterface $command);

    /**
     * Добавить доп. обработчик команды (Middleware)
     *
     * @param MiddlewareInterface $middleware
     */
    public function addMiddleware(MiddlewareInterface $middleware);
}
