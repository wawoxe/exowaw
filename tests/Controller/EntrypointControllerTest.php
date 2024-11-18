<?php

declare(strict_types=1);

/*
 * (c) Mykyta Melnyk <wawoxe@proton.me>
 *
 * This software is licensed under the MIT License. See the LICENSE file for more details.
 */
namespace App\Tests\Controller;

use function json_decode;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class EntrypointControllerTest extends WebTestCase
{
    public function testEntrypoint(): void
    {
        // Create a client to make requests
        $client = self::createClient();

        // Request to the entrypoint
        $client->request(Request::METHOD_GET, '/');

        // Check if the response status is 200
        $this->assertResponseIsSuccessful();

        // Check if the response is JSON
        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        // Decode the response and check if it's an empty array and status code is HTTP_OK
        $this->assertIsString($client->getResponse()->getContent());
        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertIsArray($data);
        $this->assertEquals([], $data);
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }
}
