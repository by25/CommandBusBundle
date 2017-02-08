<?php

/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\CommandBusBundle\Handler;

interface CommandHandlerMapper
{
    /**
     * @param $commandName
     *
     * @return callable
     */
    public function get($commandName);
}
