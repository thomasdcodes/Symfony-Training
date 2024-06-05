<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\ServerInstance;
use App\Form\ServerInstanceType;
use App\Repository\ServerInstanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route(path: '/server-instance', name: 'app_server_instance_')]
class ServerInstanceController extends AbstractController
{
    public function __construct(
        protected readonly EntityManagerInterface   $entityManager,
        protected readonly ServerInstanceRepository $serverInstanceRepository
    )
    {
    }

    #[Route(path: '/list', name: 'list', methods: ['GET'])]
    public function list(): Response
    {
        return $this->render('server-instance/list.html.twig', [
            'instances' => $this->serverInstanceRepository->findAll(),
        ]);
    }

    #[Route(path: '/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $form = $this->createForm(ServerInstanceType::class, new ServerInstance());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($form->getData());
            $this->entityManager->flush();

            $this->addFlash('success', 'Server-Instanz wurde erfolgreich angelegt.');

            return $this->redirectToRoute('app_server_instance_list');
        }

        return $this->render('server-instance/create.html.twig', [
            'serverInstanceForm' => $form,
        ]);
    }

    #[Route(path: '/{id}', name: 'read', methods: ['GET'])]
    public function read(int $id): Response
    {
        $serverInstance = $this->serverInstanceRepository->find($id);

        if (!$serverInstance instanceof ServerInstance) {
            throw new EntityNotFoundException();
        }

        return $this->render('server-instance/read.html.twig', [
            'serverInstance' => $serverInstance,
        ]);
    }

    #[Route(path: '/update/{id}', name: 'update', methods: ['GET', 'POST'])]
    public function update(ValidatorInterface $validator, int $id, Request $request): Response
    {
        $serverInstance = $this->serverInstanceRepository->find($id);

        $form = $this->createForm(ServerInstanceType::class, $serverInstance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Server-Instanz wurde erfolgreich bearbeitet.');
        }

        return $this->render('server-instance/update.html.twig', [
            'serverInstanceForm' => $form,
        ]);
    }

    #[Route(path: '/delete/{id}', name: 'delete', methods: ['GET'])]
    public function delete(int $id): Response
    {
        $serverInstance = $this->serverInstanceRepository->find($id);

        $this->entityManager->remove($serverInstance);
        $this->entityManager->flush();

        $this->addFlash('success', 'Server-Instanz wurde erfolgreich entfernt.');
        return $this->redirectToRoute('app_server_instance_list');
    }
}