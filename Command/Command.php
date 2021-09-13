<?php

/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\CommandBusBundle\Command;

interface Command
{
    public function commandName(): string;
}
