<?php

declare(strict_types=1);

namespace App\Message;

class DeleteServerInstanceMessage
{
    public function __construct(protected int $serverInstanceId)
    {
    }

    public function getServerInstanceId(): int
    {
        return $this->serverInstanceId;
    }
}