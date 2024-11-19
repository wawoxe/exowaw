<?php

declare(strict_types=1);

/*
 * (c) Mykyta Melnyk <wawoxe@proton.me>
 *
 * This software is licensed under the MIT License. See the LICENSE file for more details.
 */
namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV4;

class UserTest extends TestCase
{
    public function testUserInitialization(): void
    {
        $id             = new UuidV4;
        $identifier     = 'test@example.com';
        $identifierType = 'email';
        $roles          = ['ROLE_ADMIN'];
        $plainPassword  = 'password123';

        $user = new User(
            id: $id,
            identifier: $identifier,
            identifierType: $identifierType,
            roles: $roles,
            plainPassword: $plainPassword,
        );

        $this->assertEquals($id, $user->id);
        $this->assertEquals($identifier, $user->identifier);
        $this->assertEquals($identifierType, $user->identifierType);
        $this->assertEquals(['ROLE_ADMIN', 'ROLE_USER'], $user->getRoles());
        $this->assertEquals($plainPassword, $user->plainPassword);
    }

    public function testEraseCredentials(): void
    {
        $user = new User(
            identifier: 'test@example.com',
            identifierType: 'email',
            roles: ['ROLE_ADMIN'],
            plainPassword: 'password123',
        );

        $user->eraseCredentials();
        $this->assertNull($user->plainPassword);
    }

    public function testGetUserIdentifier(): void
    {
        $user = new User(
            identifier: 'test@example.com',
            identifierType: 'email',
        );

        $this->assertEquals('test@example.com', $user->getUserIdentifier());
    }
}
