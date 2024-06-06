<?php

namespace App\Entity;

use App\Entity\Trait\CreatedAtTrait;
use App\Entity\Trait\IdTrait;
use App\Entity\Trait\UpdatedAtTrait;
use App\Repository\CrawlingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CrawlingRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Crawling
{
    use IdTrait, CreatedAtTrait, UpdatedAtTrait;

    #[ORM\Column(length: 255)]
    private ?string $target = null;

    #[ORM\ManyToOne(inversedBy: 'crawlings', fetch: 'EAGER')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ServerInstance $serverInstance = null;

    public function getTarget(): ?string
    {
        return $this->target;
    }

    public function setTarget(string $target): static
    {
        $this->target = $target;

        return $this;
    }

    public function getServerInstance(): ?ServerInstance
    {
        return $this->serverInstance;
    }

    public function setServerInstance(?ServerInstance $serverInstance): static
    {
        $this->serverInstance = $serverInstance;

        return $this;
    }
}
