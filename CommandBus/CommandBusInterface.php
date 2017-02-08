<?php

/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\CommandBusBundle\CommandBus;

use Itmedia\CommandBusBundle\Command\Command;
use Itmedia\CommandBusBundle\Middleware\Middleware;

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
     * @param Middleware $middleware
     */
    public function addMiddleware(Middleware $middleware);
}
