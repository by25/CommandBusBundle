<?php

/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\CommandBusBundle\Tests\Stub;

use Itmedia\CommandBusBundle\Command\Command;
use Itmedia\CommandBusBundle\Command\HandlePropertiesFormArrayTrait;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;

class TestCommand implements Command
{

    use HandlePropertiesFormArrayTrait;

    /**
     * @var string
     * @NotBlank()
     */
    private $username;

    /**
     * @var string
     * @NotBlank()
     * @Assert\Email()
     */
    private $email;


    public function handle($username, $email)
    {
        $this->username = $username;
        $this->email = $email;
    }


    public function handleFromArray(array $data)
    {
        $this->handleProperties($data);
    }


    public function commandName()
    {
        return 'test_command';
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }


}