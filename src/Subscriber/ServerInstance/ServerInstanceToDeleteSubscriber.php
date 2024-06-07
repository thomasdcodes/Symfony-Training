<?php

declare(strict_types=1);

namespace App\Subscriber\ServerInstance;

use App\Event\ServerInstanceToDeleteEvent;
use App\Message\DeleteServerInstanceMessage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;
use Symfony\Component\Workflow\WorkflowInterface;

class ServerInstanceToDeleteSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            ServerInstanceToDeleteEvent::NAME => 'applyTransition'
        ];
    }

    public function __construct(
        protected readonly WorkflowInterface   $serverInstanceStateMachine,
        protected readonly MessageBusInterface $messageBus
    )
    {
    }

    /**
     * @throws ExceptionInterface
     */
    public function applyTransition(ServerInstanceToDeleteEvent $event): void
    {
        if (!$this->serverInstanceStateMachine->can($event->getServerInstance(), 'active_to_delete')) {
            return;
        }

        $this->serverInstanceStateMachine->apply($event->getServerInstance(), 'active_to_delete');
        $event->setTransitionSuccessful(true);

        $message = new DeleteServerInstanceMessage($event->getServerInstance()->getId());
        $this->messageBus->dispatch($message);

        $this->messageBus->dispatch(new Envelope($message, [
            new DelayStamp(60*60*24*30*1000),
        ]));

    }
}