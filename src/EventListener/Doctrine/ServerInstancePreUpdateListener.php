<?php

declare(strict_types=1);

namespace App\EventListener\Doctrine;

use App\Entity\ServerInstance;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::preUpdate, method: 'onPreUpdate', entity: ServerInstance::class)]
class ServerInstancePreUpdateListener
{
    public function onPreUpdate(ServerInstance $serverInstance): void
    {

    }
}