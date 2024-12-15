<?php

declare(strict_types=1);

/*
 * (c) Mykyta Melnyk <wawoxe@proton.me>
 *
 * This software is licensed under the MIT License. See the LICENSE file for more details.
 */
namespace App\Security;

use function preg_match;
use function strtr;

use App\Service\JWT\JWTService;
use Exception;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class JWTAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private readonly JWTService $jwtService,
    ) {
    }

    /**
     * Determine if this authenticator supports the current request.
     */
    public function supports(Request $request): ?bool
    {
        return $request->headers->has('Authorization');
    }

    /**
     * Authenticate the request using the JWT token.
     */
    public function authenticate(Request $request): Passport
    {
        $authHeader = $request->headers->get('Authorization');

        if (!$authHeader || !preg_match('/^Bearer\s(\S+)$/', $authHeader, $matches)) {
            throw new AuthenticationException('No valid Authorization header found');
        }

        $token = $matches[1];

        // Decode the token
        try {
            $payload = $this->jwtService->decode($token);
        } catch (Exception $e) {
            throw new AuthenticationException($e->getMessage());
        }

        // Use the `sub` claim as the username
        return new SelfValidatingPassport(new UserBadge((string) $payload['sub']));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?JsonResponse
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData()),
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
}
