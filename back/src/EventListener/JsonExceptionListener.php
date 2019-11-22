<?php

namespace App\EventListener;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class JsonExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        $code = $event->getException()->getCode();
        $code = empty($code) ? 404 : $code;

        $data = array(
            'error' => array(
                'code' => $code,
                'message' => $event->getException()->getMessage(),
            )
        );
        $response = new JsonResponse($data, $code);
        $event->setResponse($response);
    }
}