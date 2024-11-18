<?php

declare(strict_types=1);

/*
 * (c) Mykyta Melnyk <wawoxe@proton.me>
 *
 * This software is licensed under the MIT License. See the LICENSE file for more details.
 */
namespace App\Tests\EventSubscriber;

use function json_decode;

use App\EventSubscriber\ExceptionSubscriber;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;

final class ExceptionSubscriberTest extends TestCase
{
    private ExceptionSubscriber $exceptionSubscriber;

    protected function setUp(): void
    {
        $this->exceptionSubscriber = new ExceptionSubscriber;
    }

    public function testOnKernelExceptionForNotFoundHttpException(): void
    {
        $exception = new NotFoundHttpException('No route found for "GET https://localhost/not-found-page"');

        // Create a fake Request object
        $request = new Request;

        // Create ExceptionEvent instance with the exception and request
        $event = new ExceptionEvent(
            $this->createMock(HttpKernelInterface::class),  // mock the kernel
            $request,  // pass the request
            HttpKernelInterface::MAIN_REQUEST,  // Request type
            $exception,  // pass the exception
        );

        // Execute the subscriber's exception handler
        $this->exceptionSubscriber->onKernelException($event);

        // Get the response from the event
        $response = $event->getResponse();

        // Assertions to check if the response is correct
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());

        // Decode the response content to check the message
        $this->assertIsString($response->getContent());
        $data = json_decode($response->getContent(), true);

        $this->assertIsArray($data);
        $this->assertEquals('No route found for "GET https://localhost/not-found-page"', $data['message']);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $data['code']);
    }

    public function testOnKernelExceptionForGenericException(): void
    {
        $exception = new Exception('An unexpected error occurred');

        // Create a fake Request object
        $request = new Request;

        // Create ExceptionEvent instance with the exception and request
        $event = new ExceptionEvent(
            $this->createMock(HttpKernelInterface::class),  // mock the kernel
            $request,  // pass the request
            HttpKernelInterface::MAIN_REQUEST,  // Request type
            $exception,  // pass the exception
        );

        // Execute the subscriber's exception handler
        $this->exceptionSubscriber->onKernelException($event);

        // Get the response from the event
        $response = $event->getResponse();

        // Assertions to check if the response is correct
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());

        // Decode the response content to check the message
        $this->assertIsString($response->getContent());
        $data = json_decode($response->getContent(), true);

        $this->assertIsArray($data);
        $this->assertEquals('An unexpected error occurred', $data['message']);
        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $data['code']);
    }
}
