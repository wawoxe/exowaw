<?php

declare(strict_types=1);

/*
 * (c) Mykyta Melnyk <wawoxe@proton.me>
 *
 * This software is licensed under the MIT License. See the LICENSE file for more details.
 */
namespace App\Service\JWT;

use function json_encode;
use function time;

use App\Service\JWT\Exceptions\TokenExpiredException;
use JsonException;

final readonly class JWTPayload
{
    /**
     * @param array<string, int|string> $claims
     *
     * @throws TokenExpiredException
     */
    public function __construct(
        private array $claims
    ) {
        $this->validateClaims();
    }

    /**
     * @throws JsonException
     */
    public function toJson(): string
    {
        return json_encode($this->claims, JSON_THROW_ON_ERROR);
    }

    /**
     * @return array<string, int|string>
     */
    public function getClaims(): array
    {
        return $this->claims;
    }

    /**
     * @throws TokenExpiredException
     */
    private function validateClaims(): void
    {
        if (isset($this->claims['exp']) && time() >= $this->claims['exp']) {
            throw new TokenExpiredException;
        }
    }
}
