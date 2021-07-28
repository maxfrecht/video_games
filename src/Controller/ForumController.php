<?php

namespace App\Controller;

use App\Repository\ForumRepository;
use App\Repository\TopicRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ForumController extends AbstractController
{
    private ForumRepository $forumRepository;
    private EntityManagerInterface $em;
    private TopicRepository $topicRepository;

    /**
     * ForumController constructor.
     * @param ForumRepository $forumRepository
     * @param EntityManagerInterface $em
     * @param TopicRepository $topicRepository
     */
    public function __construct(ForumRepository $forumRepository, EntityManagerInterface $em, TopicRepository $topicRepository)
    {
        $this->forumRepository = $forumRepository;
        $this->em = $em;
        $this->topicRepository = $topicRepository;
    }


    #[Route('/forum', name: 'forum')]
    public function index(): Response
    {
        $forums = $this->forumRepository->findAll();

        return $this->render('forum/index.html.twig', [
            'forums' => $forums,
        ]);
    }

    #[Route('/forum/topics/{id}', name: 'forum_topics')]
    public function topicByForum(int $id): Response
    {
        $forum = $this->forumRepository->find($id);
        $topics = $this->topicRepository->findBy(['forum' => $forum]);
        return $this->render('forum/forum.topics.html.twig', [
            'forum' => $forum,
            'topics' => $topics
        ]);

    }
}