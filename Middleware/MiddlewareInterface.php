<?php

/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Infrastructure\CommandBusBundle\Middleware;

/**
 * Дополнительный обработчик команды для CommandBus
 */
interface MiddlewareInterface
{

    /**
     * Обработка команды/запроса
     *
     * @param $message
     */
    public function handle($message);
}
