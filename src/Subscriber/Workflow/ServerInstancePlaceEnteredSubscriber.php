<?php

declare(strict_types=1);

namespace App\Subscriber\Workflow;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\EnteredEvent;

class ServerInstancePlaceEnteredSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            'workflow.server_instance.entered' => ['flushChanges', 100],
            'workflow.server_instance.entered.deleted' => ['addDeletionMessage', 0],
        ];
    }

    public function __construct(protected readonly EntityManagerInterface $entityManager)
    {
    }

    public function flushChanges(EnteredEvent $event): void
    {
        $this->entityManager->flush();
    }

    public function addDeletionMessage(EnteredEvent $event): void
    {
        //Hole dir die Entity aus dem Event

        //Erstelle eine neue Message in der Zukunft (30 Tage) zum HardDelete der Entity
    }
}