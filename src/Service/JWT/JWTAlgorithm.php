<?php

declare(strict_types=1);

/*
 * (c) Mykyta Melnyk <wawoxe@proton.me>
 *
 * This software is licensed under the MIT License. See the LICENSE file for more details.
 */
namespace App\Service\JWT;

enum JWTAlgorithm: string
{
    public function getHashFunction(): string
    {
        return $this->value;
    }
    case HS256 = 'sha256';
    case HS384 = 'sha384';
    case HS512 = 'sha512';
}
