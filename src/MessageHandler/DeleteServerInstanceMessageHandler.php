<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\DeleteServerInstanceMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class DeleteServerInstanceMessageHandler
{
    public function __invoke(DeleteServerInstanceMessage $message): void
    {
        print_r(sprintf("\n%d\n", $message->getServerInstanceId()));
    }
}