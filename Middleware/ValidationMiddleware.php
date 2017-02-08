<?php

/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\CommandBusBundle\Middleware;

use Itmedia\CommandBusBundle\Command\Command;
use Itmedia\CommandBusBundle\Exception\ValidationException;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Валидатор сообщение с помощью компонента Symfony\Component\Validator
 */
class ValidationMiddleware implements Middleware
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * ValidationMiddleware constructor.
     *
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }


    public function handle(Command $message)
    {
        $violations = $this->validator->validate($message);

        if (count($violations) !== 0) {
            $errors = [];

            /**
             * @var $violation ConstraintViolationInterface
             */
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }

            throw new ValidationException($errors);
        }
    }
}
