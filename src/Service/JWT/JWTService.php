<?php

declare(strict_types=1);

/*
 * (c) Mykyta Melnyk <wawoxe@proton.me>
 *
 * This software is licensed under the MIT License. See the LICENSE file for more details.
 */
namespace App\Service\JWT;

use function array_merge;
use function base64_decode;
use function base64_encode;
use function explode;
use function hash_equals;
use function hash_hmac;
use function is_array;
use function json_decode;
use function rtrim;
use function strtr;

use function time;

use App\Service\JWT\Exceptions\InvalidTokenException;
use App\Service\JWT\Exceptions\TokenExpiredException;
use JsonException;

class JWTService
{
    private string $secretKey;
    private JWTAlgorithm $algorithm;

    public function __construct(string $secretKey, JWTAlgorithm $algorithm = JWTAlgorithm::HS256)
    {
        $this->secretKey = $secretKey;
        $this->algorithm = $algorithm;
    }

    /**
     * @param array<string, int|string> $claims
     *
     * @throws JsonException
     * @throws TokenExpiredException
     */
    public function encode(array $claims, int $ttl = 3600): string
    {
        $header  = new JWTHeader($this->algorithm);
        $payload = new JWTPayload(array_merge($claims, [
            'iat' => time(),
            'exp' => time() + $ttl,
        ]));

        $headerEncoded  = $this->base64UrlEncode($header->toJson());
        $payloadEncoded = $this->base64UrlEncode($payload->toJson());
        $signature      = $this->generateSignature("{$headerEncoded}.{$payloadEncoded}");

        return "{$headerEncoded}.{$payloadEncoded}.{$signature}";
    }

    /**
     * @throws InvalidTokenException
     * @throws JsonException
     * @throws TokenExpiredException
     *
     * @return array<string, int|string>
     */
    public function decode(string $token): array
    {
        [$headerEncoded, $payloadEncoded, $signature] = explode('.', $token);

        $expectedSignature = $this->generateSignature("{$headerEncoded}.{$payloadEncoded}");

        if (!hash_equals($expectedSignature, $signature)) {
            throw new InvalidTokenException;
        }

        $payload = json_decode($this->base64UrlDecode($payloadEncoded), true, 512, JSON_THROW_ON_ERROR);

        if (false === is_array($payload)) {
            throw new JsonException;
        }

        return (new JWTPayload($payload))->getClaims();
    }

    private function generateSignature(string $data): string
    {
        return $this->base64UrlEncode(hash_hmac(
            $this->algorithm->getHashFunction(),
            $data,
            $this->secretKey,
            true,
        ));
    }

    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function base64UrlDecode(string $data): string
    {
        return (string) base64_decode(strtr($data, '-_', '+/'), true);
    }
}
