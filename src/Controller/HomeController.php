<?php

namespace App\Controller;

use App\Repository\GameCategoryRepository;
use App\Repository\GameRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private GameRepository $gameRepository;
//    private GameCategoryRepository $gameCategoryRepository;
    private EntityManagerInterface $em;
    private PostRepository $postRepository;

    /**
     * HomeController constructor.
     * @param GameRepository $gameRepository
     * @param EntityManagerInterface $em
     * @param PostRepository $postRepository
     */
    public function __construct(GameRepository $gameRepository, EntityManagerInterface $em, PostRepository $postRepository)
    {
        $this->gameRepository = $gameRepository;
        $this->em = $em;
        $this->postRepository = $postRepository;
    }

    #[Route('/', name: 'home')]
    public function index(): Response
    {
        $games = $this->gameRepository->findAll();
        $posts = $this->postRepository->findAll();
        return $this->render('home/index.html.twig', [
            'games' => $games,
            'posts' => $posts,
            'item' => false,
        ]);
    }

    #[Route('/game/{id}', name: 'game_id')]
    public function indexId(int $id): Response
    {
        $games = $this->gameRepository->find($id);

        return $this->render('home/index.html.twig', [
            'games' => [$games],
            'item' => true,
            'posts' => null,
        ]);
    }

    #[Route('/post/{id}', name: 'post_id')]
    public function postId(int $id): Response
    {
        $post = $this->postRepository->find($id);
        $post->setNumberView($post->getNumberView() + 1);
        $this->em->persist($post);
        $this->em->flush();
        return $this->render('home/index.html.twig', [
            'games' => null,
            'item' => true,
            'posts' => [$post],
        ]);
    }
}
