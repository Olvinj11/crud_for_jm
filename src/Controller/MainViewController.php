<?php

namespace App\Controller;

use App\Entity\MainView;
use App\Form\MainViewType;
use App\Repository\MainViewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/main_views')]
final class MainViewController extends AbstractController
{
    #[Route(name: 'app_main_view_index', methods: ['GET'])]
    public function index(MainViewRepository $mainViewRepository): Response
    {
        return $this->render('main_view/index.html.twig', [
            'main_views' => $mainViewRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_main_view_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $mainView = new MainView();
        $form = $this->createForm(MainViewType::class, $mainView);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($mainView);
            $entityManager->flush();

            return $this->redirectToRoute('app_main_view_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('main_view/new.html.twig', [
            'main_view' => $mainView,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_main_view_show', methods: ['GET'])]
    public function show(MainView $mainView): Response
    {
        return $this->render('main_view/show.html.twig', [
            'main_view' => $mainView,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_main_view_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, MainView $mainView, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MainViewType::class, $mainView);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_main_view_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('main_view/edit.html.twig', [
            'main_view' => $mainView,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_main_view_delete', methods: ['POST'])]
    public function delete(Request $request, MainView $mainView, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mainView->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($mainView);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_main_view_index', [], Response::HTTP_SEE_OTHER);
    }
}
