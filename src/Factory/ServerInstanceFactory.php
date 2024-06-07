<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\ServerInstance;
use Symfony\Component\Workflow\WorkflowInterface;

readonly class ServerInstanceFactory
{
    public function __construct(protected WorkflowInterface $serverInstanceStateMachine)
    {
    }

    public function createInstance(): ServerInstance
    {
        $serverInstance = new ServerInstance();
        $serverInstance->setStatus($this->serverInstanceStateMachine->getDefinition()->getInitialPlaces()[0]);

        return $serverInstance;
    }
}