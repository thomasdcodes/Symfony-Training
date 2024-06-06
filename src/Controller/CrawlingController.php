<?php

namespace App\Controller;

use App\Entity\Crawling;
use App\Entity\ServerInstance;
use App\Form\CrawlingType;
use App\Repository\CrawlingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/crawling')]
class CrawlingController extends AbstractController
{
    #[Route('/', name: 'app_crawling_index', methods: ['GET'])]
    public function index(CrawlingRepository $crawlingRepository): Response
    {
        return $this->render('crawling/index.html.twig', [
            'crawlings' => $crawlingRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_crawling_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $crawling = new Crawling();
        $form = $this->createForm(CrawlingType::class, $crawling);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($crawling);
            $entityManager->flush();

            return $this->redirectToRoute('app_crawling_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('crawling/new.html.twig', [
            'crawling' => $crawling,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_crawling_show', methods: ['GET'])]
    public function show(Crawling $crawling): Response
    {
        return $this->render('crawling/show.html.twig', [
            'crawling' => $crawling,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_crawling_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Crawling $crawling, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CrawlingType::class, $crawling);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_crawling_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('crawling/edit.html.twig', [
            'crawling' => $crawling,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_crawling_delete', methods: ['POST'])]
    public function delete(Request $request, Crawling $crawling, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$crawling->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($crawling);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_crawling_index', [], Response::HTTP_SEE_OTHER);
    }
}
