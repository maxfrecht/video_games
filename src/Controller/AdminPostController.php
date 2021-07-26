<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\PostCategory;
use App\Form\AddPostType;
use App\Form\FiltersPostType;
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
    public function index(Request $request): Response
    {
        $form = $this->createForm(FiltersPostType::class);
        $form->handleRequest($request);
        $posts = $this->postRepository->createQueryBuilder('p');

        if($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            if($formData->getTitle()) {
                $posts
                    ->andWhere('p.title LIKE :title')
                    ->setParameter('title', '%' . $formData->getTitle() . '%');
            }
            if($formData->getContent()) {
                $posts
                    ->andWhere('p.content LIKE :content')
                    ->setParameter('content', $formData->getContent());
            }
            if($formData->getPostCategory()) {
                $posts
                    ->join('p.postCategory', 'pc')
                    ->andWhere('pc.id = :postcat')
                    ->setParameter('postcat', $formData->getPostCategory()->getId());
            }
        }
        $posts = $posts->getQuery()->getResult();
        return $this->render('admin_post/index.html.twig', [
            'posts' => $posts,
            'form' => $form->createView()
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
