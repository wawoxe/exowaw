<?php

declare(strict_types=1);

/*
 * (c) Mykyta Melnyk <wawoxe@proton.me>
 *
 * This software is licensed under the MIT License. See the LICENSE file for more details.
 */
namespace App\Service\JWT\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class InvalidTokenException extends JWTException
{
    public function __construct()
    {
        parent::__construct('jwt.token.invalid', Response::HTTP_BAD_REQUEST);
    }
}