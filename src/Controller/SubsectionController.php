<?php

namespace App\Controller;

use App\Entity\Subsection;
use App\Form\SubsectionType;
use App\Repository\SubsectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/subsection')]
final class SubsectionController extends AbstractController
{
    #[Route(name: 'app_subsection_index', methods: ['GET'])]
    public function index(SubsectionRepository $subsectionRepository): Response
    {
        return $this->render('subsection/index.html.twig', [
            'subsections' => $subsectionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_subsection_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $subsection = new Subsection();
        $form = $this->createForm(SubsectionType::class, $subsection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($subsection);
            $entityManager->flush();

            return $this->redirectToRoute('app_subsection_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('subsection/new.html.twig', [
            'subsection' => $subsection,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_subsection_show', methods: ['GET'])]
    public function show(Subsection $subsection): Response
    {
        return $this->render('subsection/show.html.twig', [
            'subsection' => $subsection,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_subsection_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Subsection $subsection, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SubsectionType::class, $subsection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_subsection_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('subsection/edit.html.twig', [
            'subsection' => $subsection,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_subsection_delete', methods: ['POST'])]
    public function delete(Request $request, Subsection $subsection, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$subsection->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($subsection);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_subsection_index', [], Response::HTTP_SEE_OTHER);
    }
}
