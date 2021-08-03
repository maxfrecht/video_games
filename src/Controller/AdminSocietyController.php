<?php

namespace App\Controller;

use App\Entity\Society;
use App\Form\SocietyType;
use App\Repository\SocietyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/society')]
class AdminSocietyController extends AbstractController
{
    #[Route('/', name: 'admin_society_index', methods: ['GET'])]
    public function index(SocietyRepository $societyRepository): Response
    {
        return $this->render('admin_society/index.html.twig', [
            'societies' => $societyRepository->findAll(),
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_society_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Society $society): Response
    {
        $form = $this->createForm(SocietyType::class, $society);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_society_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_society/edit.html.twig', [
            'society' => $society,
            'form' => $form,
        ]);
    }
}
