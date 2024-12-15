<?php

declare(strict_types=1);

/*
 * (c) Mykyta Melnyk <wawoxe@proton.me>
 *
 * This software is licensed under the MIT License. See the LICENSE file for more details.
 */
namespace App\Service\JWT\Exceptions;

use Exception;

abstract class JWTException extends Exception
{
    public function __construct(string $message, int $code = 0)
    {
        parent::__construct($message, $code);
    }
}
