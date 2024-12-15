<?php

declare(strict_types=1);

/*
 * (c) Mykyta Melnyk <wawoxe@proton.me>
 *
 * This software is licensed under the MIT License. See the LICENSE file for more details.
 */
namespace App\Service\JWT;

use function json_encode;

use JsonException;

class JWTHeader
{
    private string $alg;
    private string $typ;

    public function __construct(JWTAlgorithm $algorithm)
    {
        $this->alg = $algorithm->name;
        $this->typ = 'JWT';
    }

    /**
     * @throws JsonException
     */
    public function toJson(): string
    {
        return json_encode(['alg' => $this->alg, 'typ' => $this->typ], JSON_THROW_ON_ERROR);
    }
}
