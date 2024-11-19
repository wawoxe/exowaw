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

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_USER_IDENTIFIER', fields: ['identifier'])]
#[UniqueEntity(fields: ['identifier'], message: 'validation.user.identifier.unique')]
class User implements PasswordAuthenticatedUserInterface, UserInterface
{
    public function __construct(
        #[
            ORM\Id,
            ORM\Column(type: 'uuid'),
            ORM\GeneratedValue(strategy: 'CUSTOM'),
            ORM\CustomIdGenerator(class: 'doctrine.uuid_generator'),
        ]
        public ?Uuid $id = null,
        #[
            ORM\Column(length: 180),
            Assert\NotBlank(message: 'validation.user.identifier.notBlank'),
            Assert\Length(
                max: 180,
                maxMessage: 'validation.user.identifier.maxLength',
            ),
        ]
        public ?string $identifier = null,
        #[
            ORM\Column(length: 180),
            Assert\NotBlank(message: 'validation.user.identifierType.notBlank'),
            Assert\Choice(
                choices: ['email', 'username'],
                message: 'validation.user.identifierType.choice',
            )
        ]
        public ?string $identifierType = null,
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
        #[
            Assert\When(
                expression: 'this.password === null',
                constraints: [
                    new Assert\NotBlank(message: 'validation.user.plainPassword.notBlank'),
                ],
            ),
        ]
        public ?string $plainPassword = null,
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
        $this->plainPassword = null;
    }
}
