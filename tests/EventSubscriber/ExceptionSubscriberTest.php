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
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Throwable;

final class ExceptionSubscriberTest extends TestCase
{
    private ExceptionSubscriber $exceptionSubscriber;

    protected function setUp(): void
    {
        $this->exceptionSubscriber = new ExceptionSubscriber;
    }

    /**
     * @return array<int, array<int, Exception|int>>
     */
    public function exceptionProvider(): array
    {
        return [
            [
                new NotFoundHttpException('No route found for "GET https://localhost/not-found-page"'),
                Response::HTTP_NOT_FOUND,
            ],
            [
                new BadRequestException('Invalid request format'),
                Response::HTTP_BAD_REQUEST,
            ],
            [
                new Exception('An unexpected error occurred'),
                Response::HTTP_INTERNAL_SERVER_ERROR,
            ],
        ];
    }

    /**
     * @dataProvider exceptionProvider
     */
    public function testOnKernelException(Throwable $exception, int $expectedCode): void
    {
        // Create a fake Request object
        $request = new Request;

        // Create ExceptionEvent instance with the exception and request
        $event = new ExceptionEvent(
            $this->createMock(HttpKernelInterface::class),  // Mock the kernel
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
        $this->assertEquals($expectedCode, $response->getStatusCode());

        // Decode the response content to check the message
        $this->assertIsString($response->getContent());
        $data = json_decode($response->getContent(), true);

        $this->assertIsArray($data);
        $this->assertEquals($exception->getMessage(), $data['message']);
        $this->assertEquals($expectedCode, $data['code']);
    }
}
