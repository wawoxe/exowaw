<?php

declare(strict_types=1);

/*
 * (c) Mykyta Melnyk <wawoxe@proton.me>
 *
 * This software is licensed under the MIT License. See the LICENSE file for more details.
 */
namespace App\Entity;

use function array_unique;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_USER_IDENTIFIER', fields: ['identifier'])]
class User implements PasswordAuthenticatedUserInterface, UserInterface
{
    public function __construct(
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column]
        public ?int $id = null,
        #[ORM\Column(length: 180)]
        public ?string $identifier = null,
        /**
         * @var list<string> The user roles
         */
        #[ORM\Column]
        public array $roles = [],
        /**
         * @var null|string The hashed password
         */
        #[ORM\Column]
        public ?string $password = null,
    ) {
        $this->roles[] = 'ROLE_USER';
        $this->roles   = array_unique($this->roles);
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->identifier;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
    }
}
