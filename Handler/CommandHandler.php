<?php

/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\CommandBusBundle\Handler;

use Itmedia\CommandBusBundle\Command\Command;

interface CommandHandler
{
    public function execute(Command $command): void;
}
