<?php
declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create:user',
    description: 'Create default user',
)]
class CreateUserCommand extends Command
{
    public function __construct(
        private readonly UserRepository $userRepository
    ) {
        parent::__construct();
    }


    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'User email')
            ->addArgument('password', InputArgument::REQUIRED, 'User password');

        $this
            // the command help shown when running the command with the "--help" option
            ->setHelp('This command allows you to create a user...');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $output->writeln([
            'User Creator',
            '============',
            '',
        ]);
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        var_dump($password);
        $output->writeln('Username: ' . $email);
        $output->writeln('Password: ' . $password);


        $duplicateUser = $this
            ->userRepository
            ->findOneBy(['email' => $email]);

        if ($duplicateUser) {
            $io->error(sprintf('User "%s" has exist.', $email));

            return Command::FAILURE;
        }

        $user = new User($email, $password);
        $user->setRoles(['ROLE_USER']);
        $this->userRepository->save($user);

        $io->success(sprintf('User "%s" with password "%s" has created.', $email, $password));

        return Command::SUCCESS;
    }
}
