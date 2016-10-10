<?php

/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Infrastructure\CommandBusBundle\Command;

use Infrastructure\CommandBusBundle\Message\NamedMessageInterface;

interface CommandInterface extends NamedMessageInterface
{

}