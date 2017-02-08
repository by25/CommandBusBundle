<?php

/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\CommandBusBundle\Middleware;

use Itmedia\CommandBusBundle\Command\Command;

/**
 * Дополнительный обработчик команды для CommandBus
 */
interface Middleware
{

    /**
     * Обработка команды/запроса
     *
     * @param $message
     */
    public function handle(Command $message);
}
