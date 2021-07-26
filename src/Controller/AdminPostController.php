<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\AddPostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPostController extends AbstractController
{
    private PostRepository $postRepository;
    private EntityManagerInterface $entityManager;

    /**
     * AdminPostController constructor.
     * @param PostRepository $postRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(PostRepository $postRepository, EntityManagerInterface $entityManager)
    {
        $this->postRepository = $postRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/admin/post', name: 'admin_post')]
    public function index(): Response
    {
        $posts = $this->postRepository->findAll();
        return $this->render('admin_post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/admin/delete-post/{id}', name: 'delete_post')]
    public function deletePostCategory(int $id): Response
    {
        $post = $this->postRepository->find($id);
        $this->entityManager->remove($post);
        $this->entityManager->flush();
        return $this->redirectToRoute('admin_post');
    }

    #[Route('/admin/add-post', name: 'add_post')]
    public function addPostCategory(Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(AddPostType::class, $post);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $user =$this->getUser();
            $post->setCreatedAt(new \DateTime());
            $post->setUser($user);
            $post->setStatus(1);
            $post->setNumberView(0);
            $this->entityManager->persist($post);
            $this->entityManager->flush();
            return $this->redirectToRoute('admin_post');
        }

        return $this->render('admin_post/add-post.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/update-post/{id}', name: 'update_post')]
    public function updatePostCategory(Request $request, int $id): Response
    {
        $post = $this->postRepository->find($id);
        $form = $this->createForm(AddPostType::class, $post);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($post);
            $this->entityManager->flush();
            return $this->redirectToRoute('admin_post');
        }

        return $this->render('admin_post/add-post.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
