<?php

namespace App\Controller;

use App\Repository\ForumRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ForumController extends AbstractController
{
    private ForumRepository $forumRepository;
    private EntityManagerInterface $em;

    /**
     * ForumController constructor.
     * @param ForumRepository $forumRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(ForumRepository $forumRepository, EntityManagerInterface $em)
    {
        $this->forumRepository = $forumRepository;
        $this->em = $em;
    }

    #[Route('/forum', name: 'forum')]
    public function index(): Response
    {
        $forums = $this->forumRepository->findAll();

        return $this->render('forum/index.html.twig', [
            'forums' => $forums,
        ]);
    }
}
