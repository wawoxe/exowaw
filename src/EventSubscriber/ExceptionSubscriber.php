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
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Throwable;

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
        $event->setResponse($this->createErrorResponse($event->getThrowable()));
    }

    private function createErrorResponse(Throwable $exception): JsonResponse
    {
        $httpCode = $this->getHttpCode($exception);

        return new JsonResponse(['message' => $exception->getMessage(), 'code' => $httpCode], $httpCode);
    }

    private function getHttpCode(Throwable $exception): int
    {
        return match ($exception::class) {
            AccessDeniedHttpException::class => Response::HTTP_FORBIDDEN,
            NotFoundHttpException::class     => Response::HTTP_NOT_FOUND,
            default                          => Response::HTTP_INTERNAL_SERVER_ERROR,
        };
    }
}
