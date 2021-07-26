<?php

namespace App\Controller;

use App\Entity\PostCategory;
use App\Form\AddPostCategoryType;
use App\Form\FiltersPostCategoryType;
use App\Repository\PostCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPostCategoryController extends AbstractController
{
    private PostCategoryRepository $postCategoryRepository;
    private EntityManagerInterface $entityManager;

    /**
     * AdminPostCategoryController constructor.
     * @param PostCategoryRepository $postCategoryRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(PostCategoryRepository $postCategoryRepository, EntityManagerInterface $entityManager)
    {
        $this->postCategoryRepository = $postCategoryRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/admin/', name: 'admin')]
    public function index(): Response
    {

        return $this->render('admin_post_category/index.html.twig', [

        ]);
    }

    #[Route('/admin/get-post-category', name: 'get_post_category')]
    public function getPostCategory(Request $request): Response
    {
        $form = $this->createForm(FiltersPostCategoryType::class);
        $form->handleRequest($request);
        $posts = $this->postCategoryRepository->createQueryBuilder('p');

        if($form->isSubmitted() && $form->isValid()) {
            $posts = $posts
                ->where('p.name LIKE :formName')
                ->setParameter('formName', '%' . $form->getData()->getName() . '%');
        }

        $posts = $posts->getQuery()->getResult();

        return $this->render('admin_post_category/postcategory.html.twig', [
            'posts' => $posts,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/delete-post-category/{id}', name: 'delete_post_category')]
    public function deletePostCategory(int $id): Response
    {
        $post = $this->postCategoryRepository->find($id);
        $this->entityManager->remove($post);
        $this->entityManager->flush();
        return $this->redirectToRoute('get_post_category');
    }

    #[Route('/admin/add-post-category', name: 'add_post_category')]
    public function addPostCategory(Request $request): Response
    {
        $postCategory = new PostCategory();
        $form = $this->createForm(AddPostCategoryType::class, $postCategory);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($postCategory);
            $this->entityManager->flush();
            return $this->redirectToRoute('get_post_category');
        }

        return $this->render('admin_post_category/add-post-category.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/update-post-category/{id}', name: 'update_post_category')]
    public function updatePostCategory(Request $request, int $id): Response
    {
        $postCategory = $this->postCategoryRepository->find($id);
        $form = $this->createForm(AddPostCategoryType::class, $postCategory);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($postCategory);
            $this->entityManager->flush();
            return $this->redirectToRoute('get_post_category');
        }

        return $this->render('admin_post_category/add-post-category.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
