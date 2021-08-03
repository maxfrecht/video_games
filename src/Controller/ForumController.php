<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Topic;
use App\Form\MessageType;
use App\Form\TopicType;
use App\Repository\ForumRepository;
use App\Repository\GameRepository;
use App\Repository\MessageRepository;
use App\Repository\TopicRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ForumController extends AbstractController
{
    private ForumRepository $forumRepository;
    private EntityManagerInterface $em;
    private TopicRepository $topicRepository;
    private MessageRepository $messageRepository;
    private GameRepository $gameRepository;

    /**
     * ForumController constructor.
     * @param ForumRepository $forumRepository
     * @param EntityManagerInterface $em
     * @param TopicRepository $topicRepository
     * @param MessageRepository $messageRepository
     * @param GameRepository $gameRepository
     */
    public function __construct(ForumRepository $forumRepository, EntityManagerInterface $em, TopicRepository $topicRepository, MessageRepository $messageRepository, GameRepository $gameRepository)
    {
        $this->forumRepository = $forumRepository;
        $this->em = $em;
        $this->topicRepository = $topicRepository;
        $this->messageRepository = $messageRepository;
        $this->gameRepository = $gameRepository;
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

    #[Route('/forum/topics/game/{id}', name: 'forum_topics_game')]
    public function topicByGame(int $id): Response
    {
        $game = $this->gameRepository->find($id);
        $forums = $this->forumRepository->findBy(['game' => $game]);
        if($forums) {
            $forum = $forums[0];
            $topics = $this->topicRepository->findBy(['forum' => $forum]);
        } else {
            $forum = null;
            $topics = [];
        }

        return $this->render('forum/forum.topics.html.twig', [
            'forum' => $forum,
            'topics' => $topics
        ]);
    }

    #[Route('/forum/topics/{id}/create', name: 'forum_topic_create')]
    public function createTopic(Request $request, int $id): Response
    {
        $topic = new Topic();
        $forum = $this->forumRepository->find($id);
        $form = $this->createForm(TopicType::class, $topic);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $topic->setUser($user);
            $topic->setTitle($form->getData()->getTitle());
            $topic->setPathLogo($form->getData()->getPathLogo());
            $topic->setForum($forum);
            $topic->setCreatedAt(new \DateTime());
            $this->em->persist($topic);
            $this->em->flush();
            return $this->redirectToRoute('forum_topics', ['id' => $id]);
        }
        return $this->render('forum/add-topic.html.twig', [
            'form' => $form->createView(),
            'forum' => $forum,
        ]);
    }

    #[Route('/forum/topics/{id}/edit/{idTopic}', name: 'admin_topic_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Topic $topic, $id, $idTopic): Response
    {
        $forum = $this->forumRepository->find($id);
        $topic = $this->topicRepository->find($idTopic);
        $form = $this->createForm(TopicType::class, $topic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('forum_topics', ['id' => $id]);
        }

        return $this->renderForm('forum/add-topic.html.twig', [
            'topic' => $topic,
            'form' => $form,
            'forum' => $forum,
        ]);
    }

    #[Route('/forum/topics/{id}/message', name: 'topic_messages')]
    public function messageTopic(int $id, Request $request): Response
    {
        $message = new Message();
        $topic = $this->topicRepository->find($id);
        $messages = $this->messageRepository->findBy(['topic' => $topic],['createdAt' => 'ASC']);
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        $user = $this->getUser();
        if($user === null) {
            return $this->redirectToRoute('app_login');
        }

        if($form->isSubmitted() && $form->isValid()) {
            dump($form->getData());
            dump('coucou');

            $message->setContent($form->getData()->getContent());
            $message->setUser($user);
            $message->setCreatedAt(new \DateTime());
            $message->setTopic($topic);
            $this->em->persist($message);
            $this->em->flush();
            return $this->redirectToRoute('topic_messages', ['id' => $topic->getId()]);
        }

        return $this->render('forum/topic.messages.html.twig', [
            'topic' => $topic,
            'messages' => $messages,
            'form' => $form->createView()
        ]);
    }

    #[Route('/forum/topics/{id}/messages/{idMessage}/edit', name: 'message_edit', methods: ['GET', 'POST'])]
    public function editMessage(Request $request, Message $message, $id, $idMessage): Response
    {
        $topic = $this->topicRepository->find($id);
        $messages = $this->messageRepository->findBy(['topic' => $topic],['createdAt' => 'ASC']);
        $message = $this->messageRepository->find($idMessage);
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('topic_messages', ['id' => $topic->getId()]);
        }

        return $this->renderForm('forum/topic.messages.html.twig', [
            'messages' => $messages,
            'form' => $form,
            'topic' => $topic,
            'message' => $message
        ]);
    }

    #[Route('/forum/topics/{id}/messages/{idMessage}/delete', name: 'message_delete', methods: ['GET', 'POST'])]
    public function deleteMessage($id, $idMessage): Response
    {
        $topic = $this->topicRepository->find($id);
        $message = $this->messageRepository->find($idMessage);
        $this->em->remove($message);
        $this->em->flush();
        return $this->redirectToRoute('topic_messages', ['id' => $topic->getId()]);
    }

}