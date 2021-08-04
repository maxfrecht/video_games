<?php

namespace App\Command;

use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:check-messages',
)]
class CheckUserMessageCommand extends Command
{
    const BANNED_WORDS = [
        'merde',
        'jules',
        'putain',
        'nique',
        'ntm',
        'connard',
        'con',
        'salope',
        'enculé',
        'encule',
        'tongue',
        'poulet',
        'pierre',
        'feuille',
        'ciseaux',
        'pute'
    ];
    private MessageRepository $messageRepository;
    private EntityManagerInterface $em;

    /**
     * @param MessageRepository $messageRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(MessageRepository $messageRepository, EntityManagerInterface $em)
    {
        parent::__construct();
        $this->messageRepository = $messageRepository;
        $this->em = $em;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('check user messages and ban them if rules are not respected')
            ->setHelp('check user messages and ban them if rules are not respected');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Check messages');
        $messages = $this->messageRepository->findBy(['isAnalysed' => false]);
        $userBanned = [];
        foreach($messages as $message) {
            $user = $message->getUser();
            foreach(CheckUserMessageCommand::BANNED_WORDS as $word) {
                if(str_contains($message->getContent(), $word)) {
                    $user->setNbrBannedMessages($user->getNbrBannedMessages() + 1);
                    $message->setContent('Le contenu de ce message était trop vulgaire et a été supprimé');
                    if($user->getNbrBannedMessages() >= 3) {
                        $user->setIsBanned(true);
                        if(!in_array($user, $userBanned))
                        $userBanned[] = $user;
                    }
                    $this->em->persist($user);
                }
            }
            $message->setIsAnalysed(true);
            $this->em->persist($message);
        }
        $this->em->flush();
        if(count($userBanned) > 0) {
            foreach($userBanned as $userB) {
                $io->success('The user ' . $userB->getEmail() . ' is now banned with ' . $userB->getNbrBannedMessages() . ' words found');
            }
        }
        return Command::SUCCESS;
    }
}
