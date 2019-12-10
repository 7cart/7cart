<?php

namespace App\EventListener;

use Vich\UploaderBundle\Event\Event;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class VichListener
{

    private $_validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->_validator = $validator;
    }
    public function onVichUploaderPreUpload(Event $event)
    {
        $object = $event->getObject();
        $errors = $this->_validator->validate($object);

        if (count($errors) > 0) {
             throw new BadRequestHttpException($errors[0]->getMessage());
        }
    }
}