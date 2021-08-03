<?php

namespace App\Controller;

use App\Repository\ContactMessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminContactMessageController extends AbstractController
{
    private ContactMessageRepository $contactMessageRepository;
    private EntityManagerInterface $em;

    /**
     * AdminContactMessageController constructor.
     * @param ContactMessageRepository $contactMessageRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(ContactMessageRepository $contactMessageRepository, EntityManagerInterface $em)
    {
        $this->contactMessageRepository = $contactMessageRepository;
        $this->em = $em;
    }

    #[Route('/admin/message', name: 'admin_message')]
    public function index(): Response
    {
        $messages = $this->contactMessageRepository->findAll();

        return $this->render('admin_contact_message/index.html.twig', [
            'messages' => $messages,
        ]);
    }

    #[Route('/admin/message/show/{id}', name: 'admin_contact_message_show')]
    public function indexShow(int $id): Response
    {
        $message = $this->contactMessageRepository->find($id);

        return $this->render('admin_contact_message/index.html.twig', [
            'messages' => [$message],
        ]);
    }

    #[Route('/admin/message/{id}', name: 'admin_message_readen')]
    public function read(int $id): Response
    {
        $message = $this->contactMessageRepository->find($id);
        $message->setIsRead(1);
        $message->setReadAt(new \DateTime());
        $this->em->persist($message);
        $this->em->flush();
        return $this->redirectToRoute('admin_message');
    }

    #[Route('/admin/message/not-read/{id}', name: 'admin_message_not_readen')]
    public function notRead(int $id): Response
    {
        $message = $this->contactMessageRepository->find($id);
        $message->setIsRead(0);
        $message->setReadAt(null);
        $this->em->persist($message);
        $this->em->flush();
        return $this->redirectToRoute('admin_message');
    }
}
