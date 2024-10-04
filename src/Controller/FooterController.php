<?php

namespace App\Controller;

use App\Entity\Footer;
use App\Form\FooterType;
use App\Repository\FooterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/footer')]
final class FooterController extends AbstractController
{
    #[Route(name: 'app_footer_index', methods: ['GET'])]
    public function index(FooterRepository $footerRepository): Response
    {
        return $this->render('footer/index.html.twig', [
            'footers' => $footerRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_footer_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $footer = new Footer();
        $form = $this->createForm(FooterType::class, $footer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($footer);
            $entityManager->flush();

            return $this->redirectToRoute('app_footer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('footer/new.html.twig', [
            'footer' => $footer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_footer_show', methods: ['GET'])]
    public function show(Footer $footer): Response
    {
        return $this->render('footer/show.html.twig', [
            'footer' => $footer,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_footer_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Footer $footer, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FooterType::class, $footer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_footer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('footer/edit.html.twig', [
            'footer' => $footer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_footer_delete', methods: ['POST'])]
    public function delete(Request $request, Footer $footer, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$footer->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($footer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_footer_index', [], Response::HTTP_SEE_OTHER);
    }
}
