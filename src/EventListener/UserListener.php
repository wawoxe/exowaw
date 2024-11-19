<?php

declare(strict_types=1);

/*
 * (c) Mykyta Melnyk <wawoxe@proton.me>
 *
 * This software is licensed under the MIT License. See the LICENSE file for more details.
 */
namespace App\EventListener;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsEntityListener(event: Events::preFlush, method: 'hashPassword', entity: User::class)]
final readonly class UserListener
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function hashPassword(User $user): void
    {
        if (null !== $user->plainPassword) {
            $user->password = $this->passwordHasher->hashPassword($user, $user->plainPassword);
            $user->eraseCredentials();
        }
    }
}
