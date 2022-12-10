<?php
declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create:admin',
    description: 'Create admin user',
)]
class CreateAdminCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        parent::__construct();

    }


    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'User email')
            ->addArgument('password', InputArgument::REQUIRED, 'User password')
        ;

        $this
            // the command help shown when running the command with the "--help" option
            ->setHelp('This command allows you to create a user...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $output->writeln([
            'Admin Creator',
            '============',
            '',
        ]);
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        $output->writeln('Username: ' . $email);
        $output->writeln('Password: '. $password);

        $helper = $this->getHelper('question');
        $question = new ChoiceQuestion(sprintf('Do you want give admin to "%s": ', $email), ['yes', 'no'], 'no');
        $question->setErrorMessage('Answer "%s" is invalid.');

        $duplicateUser = $this->userRepository->findOneBy(['email' => $email]);
        if (!$duplicateUser) {
            $user = new User($email, $password);
            $user->setRoles(['ROLE_ADMIN']);
            $this->userRepository->save($user);

            $io->success(
                sprintf('User "%s" with password "%s" has created.', $email, $password)
            );

            return Command::SUCCESS;
        }

        $io->error(
            sprintf('User "%s" has exist.', $email)
        );

        $giveAdmin = $helper->ask($input, $output, $question);

        if ($giveAdmin === 'no') {
            $io->error(
                sprintf('User "%s" can`t create.', $email)
            );

            return Command::FAILURE;
        }

        if ($duplicateUser->equalsPassword($password)) {
            $io->error('Invalid password!');

            return Command::FAILURE;
        }

        $duplicateUser->setRoles(['ROLE_ADMIN']);
        $this->entityManager->flush();

        $io->success(
            sprintf('The user "%s" is now an admin', $email)
        );

        return Command::SUCCESS;
    }
}
