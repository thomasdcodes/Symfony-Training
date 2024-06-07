<?php

declare(strict_types=1);

namespace App\Event;

use App\Entity\ServerInstance;
use Symfony\Contracts\EventDispatcher\Event;

class ServerInstanceToDeleteEvent extends Event
{
    public const NAME = 'app.server_instance_to_delete_event';
    protected bool $transitionSuccessful = false;

    public function __construct(protected readonly ServerInstance $serverInstance)
    {
    }

    public function getServerInstance(): ServerInstance
    {
        return $this->serverInstance;
    }

    public function setTransitionSuccessful(bool $transitionSuccessful): void
    {
        $this->transitionSuccessful = $transitionSuccessful;
    }

    public function isTransitionSuccessful(): bool
    {
        return $this->transitionSuccessful;
    }
}