<?php

/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\CommandBusBundle\Handler;

interface CommandHandlerMapper
{
    /**
     * @return callable
     */
    public function get(string $commandName);
}
