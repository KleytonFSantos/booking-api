<?php

namespace App\Exception;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionHandler implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::EXCEPTION => 'handleException'];
    }

    public function handleException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $response = new JsonResponse(
            [
                'error' => $exception->getMessage(),
            ],
            $exception->getCode()
        );

        $event->setResponse($response);
    }
}
