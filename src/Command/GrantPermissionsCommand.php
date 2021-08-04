<?php

namespace App\Command;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:new-admin',
)]
class GrantPermissionsCommand extends Command
{
    private UserRepository $userRepository;
    private EntityManagerInterface $em;

    /**
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(UserRepository $userRepository, EntityManagerInterface $em)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->em = $em;
    }

    protected function configure(): void
    {
        $this
        ->setDescription('Grant admin role to the given user')
        ->setHelp('Grant permission to the given user');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Grant admin permission to given user');
        $email = $io->ask("What's the email of the user ?");
        $user = $this->userRepository->findOneBy(['email' => $email]);
        if($user) {
            $io->text('User founded, granting permissions');
            $io->progressStart(100);
            $user->setRoles(['ROLE_ADMIN']);
            $io->progressAdvance(50);
            $this->em->persist($user);
            $io->progressAdvance(75);
            $this->em->flush();
            $io->progressFinish();
            $io->success('The user ' . $user->getEmail() . ' is now granted with the admin role');
            return Command::SUCCESS;
        } else {
            $io->caution("User not found in database");
            return Command::FAILURE;
        }

    }
}
