<?php

/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\CommandBusBundle\Command;

interface Command
{
    /**
     * Имя сообщения
     * @return string
     */
    public function commandName();
}
