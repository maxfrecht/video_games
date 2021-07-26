<?php

namespace App\Controller;

use App\Entity\ContactMessage;
use App\Form\ContactType;
use App\Repository\ContactMessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    private ContactMessageRepository $contactMessageRepository;
    private EntityManagerInterface $em;

    /**
     * ContactController constructor.
     * @param ContactMessageRepository $contactMessageRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(ContactMessageRepository $contactMessageRepository, EntityManagerInterface $em)
    {
        $this->contactMessageRepository = $contactMessageRepository;
        $this->em = $em;
    }

    #[Route('/contact', name: 'contact')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $message = new ContactMessage();
            $message->setIsRead(false);
            $message->setMessage($form->getData()->getMessage());
            $message->setUser($user);
            $message->setSubject($form->getData()->getSubject());
            $this->em->persist($message);
            $this->em->flush();
            return $this->redirectToRoute('home', ['message'=>true]);
        }
        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
