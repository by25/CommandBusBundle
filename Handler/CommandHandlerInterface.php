<?php

/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Infrastructure\CommandBusBundle\Handler;

use Infrastructure\CommandBusBundle\Command\CommandInterface;

interface CommandHandlerInterface
{
    public function execute(CommandInterface $command);
}