<?php

/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Infrastructure\CommandBusBundle\Message;

/**
 * Именнованная комманда/сообщение
 */
interface NamedMessageInterface
{
    /**
     * Имя сообщения
     * @return string
     */
    public function messageName();
}
