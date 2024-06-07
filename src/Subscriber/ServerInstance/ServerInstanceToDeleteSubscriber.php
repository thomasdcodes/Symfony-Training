<?php

declare(strict_types=1);

namespace App\Subscriber\ServerInstance;

use App\Event\ServerInstanceToDeleteEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\WorkflowInterface;

class ServerInstanceToDeleteSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            ServerInstanceToDeleteEvent::NAME => 'applyTransition'
        ];
    }

    public function __construct(protected readonly WorkflowInterface $serverInstanceStateMachine,)
    {
    }

    public function applyTransition(ServerInstanceToDeleteEvent $event): void
    {
        if (!$this->serverInstanceStateMachine->can($event->getServerInstance(), 'active_to_delete')) {
            return;
        }

        $this->serverInstanceStateMachine->apply($event->getServerInstance(), 'active_to_delete');
        $event->setTransitionSuccessful(true);
    }
}