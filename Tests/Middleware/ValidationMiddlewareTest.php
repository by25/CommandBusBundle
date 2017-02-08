<?php

/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\CommandBusBundle\Tests\Middleware;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\DocParser;
use Itmedia\CommandBusBundle\Exception\ValidationException;
use Itmedia\CommandBusBundle\Middleware\ValidationMiddleware;
use Itmedia\CommandBusBundle\Tests\Stub\TestCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\NotBlank;

class ValidationMiddlewareTest extends TestCase
{
    public function testHandleFailed()
    {
        $this->loadConstraintsClass();

        $command = new TestCommand();
        $command->handle('Test', 'aaa');


        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();

        $middleware = new ValidationMiddleware($validator);

        try {
            $middleware->handle($command);
            $this->fail('Must throw ' . ValidationException::class);
        } catch (ValidationException $exception) {
            $this->assertArrayNotHasKey('username', $exception->getMessages());
            $this->assertArrayHasKey('email', $exception->getMessages());
        }
    }


    public function testHandleSuccess()
    {
        $this->loadConstraintsClass();

        $command = new TestCommand();
        $command->handle('Test', 'aaa@test.by');


        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();

        $middleware = new ValidationMiddleware($validator);

        $middleware->handle($command);
    }


    private function loadConstraintsClass()
    {
        new NotBlank();
        new Email();
    }
}
