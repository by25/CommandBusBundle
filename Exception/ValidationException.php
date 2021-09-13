<?php

/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\CommandBusBundle\Exception;

use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationException extends \RuntimeException
{
    protected array $messages = [];

    /**
     * {@inheritdoc}
     */
    public function __construct(array $messages, $code = 400, \Exception $previous = null)
    {
        $this->messages = $messages;
        parent::__construct(count($messages) ? reset($this->messages) : '', $code, $previous);
    }


    public static function createFromConstraintViolationList(ConstraintViolationListInterface $list): self
    {
        $messages = [];
        /**
         * @var ConstraintViolationInterface $violation
         */
        foreach ($list as $violation) {
            $messages[$violation->getPropertyPath()] = $violation->getMessage();
        }

        return new self($messages);
    }


    public function getMessages(): array
    {
        return $this->messages;
    }

    public function getTranslatedMessages(TranslatorInterface $translator): array
    {
        $result = [];
        foreach ($this->messages as $key => $message) {
            $result[$key] = $translator->trans($message);
        }

        return $result;
    }
}
