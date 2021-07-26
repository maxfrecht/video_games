<?php

namespace App\Controller;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    private UserRepository $userRepository;
    private EntityManagerInterface $em;

    /**
     * AccountController constructor.
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(UserRepository $userRepository, EntityManagerInterface $em)
    {
        $this->userRepository = $userRepository;
        $this->em = $em;
    }

    #[Route('/account', name: 'account')]
    public function index(Request $request): Response
    {
        if( $request->query->get('update')) {
            $update = true;
        } else {
            $update = false;
        }
        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
            'update' => $update
        ]);
    }

    #[Route('/update-user', name: 'update_user')]
    public function updateUser(Request $request): ?Response
    {
            $firstname = $request->request->get('firstname');
            $lastName = $request->request->get('lastname');
            $email = $request->request->get('email');
            $avatar = $request->request->get('avatar');
            $user = $this->getUser();
            $user->setFirstName($firstname);
            $user->setLastName($lastName);
            $user->setAvatar($avatar);
            $user->setEmail($email);
            $this->em->persist($user);
            $this->em->flush();
            return $this->redirectToRoute('account');

    }
}
