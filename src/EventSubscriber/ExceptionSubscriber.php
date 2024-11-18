<?php

declare(strict_types=1);

/*
 * (c) Mykyta Melnyk <wawoxe@proton.me>
 *
 * This software is licensed under the MIT License. See the LICENSE file for more details.
 */
namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException', 100],
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        // Prepare the error response data
        $data = ['message' => $exception->getMessage()];

        // Set up status code by exception class name
        $data['code'] = match ($exception::class) {
            NotFoundHttpException::class => Response::HTTP_NOT_FOUND,
            default                      => Response::HTTP_INTERNAL_SERVER_ERROR,
        };

        // Create the JSON response
        $response = new JsonResponse($data, $data['code']);

        $event->setResponse($response);
    }
}
