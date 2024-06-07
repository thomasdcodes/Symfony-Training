<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\ServerInstance;
use App\Event\ServerInstanceToDeleteEvent;
use App\Factory\ServerInstanceFactory;
use App\Form\ServerInstanceType;
use App\Repository\ServerInstanceRepository;
use App\Security\Voter\ServerInstanceVoter;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Workflow\WorkflowInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

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
            'instances' => $this->serverInstanceRepository->findActive(),
        ]);
    }

    #[Route(path: '/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(Request $request, ServerInstanceFactory $factory): Response
    {
        $form = $this->createForm(ServerInstanceType::class, $factory->createInstance());
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

        $this->denyAccessUnlessGranted(ServerInstanceVoter::VIEW, $serverInstance);

        if (!$serverInstance instanceof ServerInstance) {
            throw new EntityNotFoundException();
        }

        return $this->render('server-instance/read.html.twig', [
            'serverInstance' => $serverInstance,
        ]);
    }

    #[Route(path: '/update/{id}', name: 'update', methods: ['GET', 'POST'])]
    public function update(int $id, Request $request): Response
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
    public function delete(
        int                      $id,
        EventDispatcherInterface $eventDispatcher
    ): Response
    {
        $serverInstance = $this->serverInstanceRepository->find($id);

        $event = new ServerInstanceToDeleteEvent($serverInstance);
        $eventDispatcher->dispatch($event, $event::NAME);

        if($event->isTransitionSuccessful()) {
            $this->addFlash('success', 'Server-Instanz wurde erfolgreich entfernt.');
            return $this->redirectToRoute('app_server_instance_list');
        } else {
            $this->addFlash('warning', 'Server-Instanz darf nicht gelöscht werden.');
            return $this->redirectToRoute('app_crawling_show', ['id' => $id]);
        }
    }
}