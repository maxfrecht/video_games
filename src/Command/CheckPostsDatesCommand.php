<?php

namespace App\Command;

use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:check-posts',
)]
class CheckPostsDatesCommand extends Command
{
    private PostRepository $postsRepository;
    private EntityManagerInterface $entityManagerInterface;

    /**
     * @param PostRepository $postsRepository
     * @param EntityManagerInterface $entityManagerInterface
     */
    public function __construct(PostRepository $postsRepository, EntityManagerInterface $entityManagerInterface)
    {
        parent::__construct();
        $this->postsRepository = $postsRepository;
        $this->entityManagerInterface = $entityManagerInterface;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Check if posts are outdated and set the correct status')
            ->setHelp('Check if posts are outdated and set the correct status');;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Check outdated posts');
        $date = $io->ask('Avant quel date les actualités sont elles considéré comme expirées ?');
        $regex = '/[1-2][0-9]{3}-[0-1][0-9]-[0-3][0-9]/';

        if (isset($date) && preg_match($regex, $date)) {
            $posts = $this->postsRepository->createQueryBuilder('post')
                ->andWhere('post.createdAt <= :date')
                ->setParameter('date', $date)
                ->getQuery()->getResult();

            if (count($posts) > 0) {
                foreach ($posts as $post) {
                    $post->setStatus(0);
                    $this->entityManagerInterface->persist($post);
                }
                $this->entityManagerInterface->flush();
                $io->success(count($posts) . ' Posts found and updated !');
            } else {
                $io->caution('no Post Found');
            }
            return Command::SUCCESS;
        }
        $io->caution("ça marche pas tu ne sais pas écrire une date correctement...");
        return Command::FAILURE;
    }
}
