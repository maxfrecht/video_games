<?php

namespace App\Controller;

use App\Entity\Topic;
use App\Form\TopicType;
use App\Repository\TopicRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/topic')]
class AdminTopicController extends AbstractController
{
    #[Route('/', name: 'admin_topic_index', methods: ['GET'])]
    public function index(TopicRepository $topicRepository): Response
    {
        return $this->render('admin_topic/index.html.twig', [
            'topics' => $topicRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_topic_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $topic = new Topic();
        $form = $this->createForm(TopicType::class, $topic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $topic->setCreatedAt(new DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($topic);
            $entityManager->flush();

            return $this->redirectToRoute('admin_topic_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_topic/new.html.twig', [
            'topic' => $topic,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_topic_show', methods: ['GET'])]
    public function show(Topic $topic): Response
    {
        return $this->render('admin_topic/show.html.twig', [
            'topic' => $topic,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_topic_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Topic $topic): Response
    {
        $form = $this->createForm(TopicType::class, $topic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_topic_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_topic/edit.html.twig', [
            'topic' => $topic,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_topic_delete', methods: ['POST'])]
    public function delete(Request $request, Topic $topic): Response
    {
        if ($this->isCsrfTokenValid('delete'.$topic->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($topic);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_topic_index', [], Response::HTTP_SEE_OTHER);
    }
}
