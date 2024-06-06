<?php

declare(strict_types=1);

namespace App\Entity\Trait;

use App\Factory\DateTimeFactory;
use Doctrine\ORM\Mapping as ORM;

trait CreatedAtTrait
{
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function initCreatedAt(): void
    {
        $this->createdAt = DateTimeFactory::createNowImmutable();
    }
}