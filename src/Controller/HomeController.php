<?php

namespace App\Controller;

use App\Form\FiltersGameType;
use App\Repository\GameCategoryRepository;
use App\Repository\GameRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/game-list', name: 'game_list')]
    public function gameList(PaginatorInterface $paginator, Request $request): Response
    {
        $form = $this->createForm(FiltersGameType::class);
        $form->handleRequest($request);
//        die();

        $qb = $this->gameRepository->createQueryBuilder('game');

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            dump($data);
            if ($data['title']) {
                $qb
                    ->andWhere('game.title LIKE :title')
                    ->setParameter('title', '%' . $data['title'] . '%');
            }
            if($data['pricemin'] && $data['pricemax']) {
                $qb
                    ->andWhere('game.price >= :pricemin')
                    ->setParameter('pricemin', $data['pricemin'])
                    ->andWhere('game.price <= :pricemax')
                    ->setParameter('pricemax', $data['pricemax']);
            }
            if($data['noteGlobalMin'] && $data['noteGlobalMax']) {
                $qb
                    ->andWhere('game.noteGlobal >= :noteGlobalMin')
                    ->setParameter('noteGlobalMin', $data['noteGlobalMin'])
                    ->andWhere('game.noteGlobal <= :noteGlobalMax')
                    ->setParameter('noteGlobalMax', $data['noteGlobalMax']);
            }
            if($data['gameCategory']) {
                $qb
                    ->join('game.gameCategory', 'gc')
                    ->andWhere('gc.id = :postcat')
                    ->setParameter('postcat', $data['gameCategory']->getId());
            }
            if($data['devices']) {
                $qb
                    ->join('game.devices', 'd')
                    ->andWhere('d.id = :device')
                    ->setParameter('device', $data['devices']->getId());
            }
        }
        $pagination = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('home/game-list.html.twig', [
            'games' => $pagination,
            'item' => false,
            'form' => $form->createView()
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
