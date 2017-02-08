<?php

/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\CommandBusBundle\Tests\Command;

use Itmedia\CommandBusBundle\Tests\Stub\TestCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\NotBlank;

class HandlerPropertiesFromArrayTraitTest extends TestCase
{


    /**
     * @param $data
     * @param $email
     * @param $username
     *
     * @dataProvider provideArrayCommand
     */
    public function testHandleFromArray($data, $username, $email)
    {
                $command = new TestCommand();
        $command->handleFromArray($data);

        $this->assertEquals($command->getEmail(), $email);
        $this->assertEquals($command->getUsername(), $username);

    }

    public function provideArrayCommand()
    {
        return [
            [['username' => 'User', 'email' => 'test@test.com'], 'User', 'test@test.com'],
            [['username' => 'User', 'email' => 'test@test.com', 'other_key' => 1], 'User', 'test@test.com'],
            [['email' => 'test@test.com'], null, 'test@test.com'],
        ];
    }


}