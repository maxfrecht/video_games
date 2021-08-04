<?php

namespace App\Command;

use App\Entity\GameCategory;
use App\Repository\GameCategoryRepository;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-game-cat',
)]
class createGameCategoryCommand extends Command
{
    private GameCategoryRepository $gameCategoryRepository;
    private EntityManagerInterface $em;

    /**
     * @param GameRepository $gameRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(GameCategoryRepository $gameCategoryRepository, EntityManagerInterface $em)
    {
        parent::__construct();
        $this->gameCategoryRepository = $gameCategoryRepository;
        $this->em = $em;
    }

    protected function configure(): void
    {
        $this->setDescription('Create a game categorie')
            ->setHelp('This command create a game categorie');
//            ->addArgument('categoryName', InputArgument::REQUIRED, "the category name");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Create a game category');
        $gameCategory = new GameCategory();
        $name = $io->ask('What is the name of the category ?');
        $gameCategory->setName($name);
        $this->em->persist($gameCategory);
        $this->em->flush();
        $output->writeln('La catégorie ' . $name . ' a bien été créée');
        return Command::SUCCESS;
    }
}
