<?php

/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Infrastructure\CommandBusBundle\Exception;

use Symfony\Component\Translation\TranslatorInterface;

class ValidationException extends \RuntimeException
{
    protected $messages = [];

    /**
     * {@inheritdoc}
     */
    public function __construct(array $messages, $code = 400, \Exception $previous = null)
    {
        $this->messages = $messages;
        parent::__construct(count($messages) ? reset($this->messages) : '', $code, $previous);
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }


    /**
     * Переведенные сообщения об ошибках
     *
     * @param TranslatorInterface $translator
     * @return array
     *
     * @throws \Exception
     */
    public function getTranslatedMessages(TranslatorInterface $translator)
    {
        $result = [];
        foreach ($this->messages as $key => $message) {
            $result[$key] = $translator->trans($message);
        }

        return $result;
    }
}
