<?php

declare(strict_types=1);

/*
 * (c) Mykyta Melnyk <wawoxe@proton.me>
 *
 * This software is licensed under the MIT License. See the LICENSE file for more details.
 */
namespace App\Command\Make;

use function is_string;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

#[AsCommand(
    name: 'app:installation:make:admin',
    description: 'Create administration user',
)]
final class AdminCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $manager,
        private readonly ValidatorInterface $validator,
        ?string $name = null,
    ) {
        parent::__construct($name);
    }

    /**
     * @throws Throwable
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $symfonyStyle = new SymfonyStyle($input, $output);

        $identifierType = $input->getArgument('identifierType');
        $identifier     = $input->getArgument('identifier');
        $password       = $input->getArgument('password');

        if (is_string($identifierType) && is_string($identifier) && is_string($password)) {
            $user = new User(
                identifier: $identifier,
                identifierType: $identifierType,
                roles: ['ROLE_ADMIN'],
                plainPassword: $password,
            );

            $validationErrors = $this->validator->validate($user);

            foreach ($validationErrors as $validationError) {
                $symfonyStyle->error((string) $validationError->getMessage());

                return Command::FAILURE;
            }

            $this->manager->persist($user);
            $this->manager->flush();

            $symfonyStyle->success('Administrator was created');

            return Command::SUCCESS;
        }

        $symfonyStyle->success('Invalid arguments');

        return Command::FAILURE;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('identifierType', InputArgument::REQUIRED, 'Identifier type')
            ->addArgument('identifier', InputArgument::REQUIRED, 'Admin identifier')
            ->addArgument('password', InputArgument::REQUIRED, 'Admin password');
    }
}
