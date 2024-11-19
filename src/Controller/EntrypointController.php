<?php

declare(strict_types=1);

/*
 * (c) Mykyta Melnyk <wawoxe@proton.me>
 *
 * This software is licensed under the MIT License. See the LICENSE file for more details.
 */
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route(name: 'api_entrypoint_')]
class EntrypointController extends AbstractController
{
    /**
     * Entry point for the API.
     */
    #[Route(
        path: '/',
        name: 'read',
        methods: ['GET'],
    )]
    public function read(): JsonResponse
    {
        return $this->json([]);
    }
}
