<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Trait\CreatedAtTrait;
use App\Entity\Trait\IdTrait;
use App\Entity\Trait\UpdatedAtTrait;
use App\Repository\ServerInstanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ServerInstanceRepository::class)]
#[ORM\HasLifecycleCallbacks]
class ServerInstance
{
    use IdTrait, CreatedAtTrait, UpdatedAtTrait;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 32, nullable: true)]
    #[Assert\Length(min: 12, max: 32)]
    private ?string $baseUrl = null;

    /**
     * @var Collection<int, Crawling>
     */
    #[ORM\OneToMany(targetEntity: Crawling::class, mappedBy: 'serverInstance', orphanRemoval: true)]
    private Collection $crawlings;

    #[ORM\Column(length: 20)]
    private ?string $status = null;

    public function __construct()
    {
        $this->crawlings = new ArrayCollection();
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getBaseUrl(): ?string
    {
        return $this->baseUrl;
    }

    public function setBaseUrl(?string $baseUrl): static
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    /**
     * @return Collection<int, Crawling>
     */
    public function getCrawlings(): Collection
    {
        return $this->crawlings;
    }

    public function getCrawlingCount(): int
    {
        return $this->crawlings->count();
    }

    public function addCrawling(Crawling $crawling): static
    {
        if (!$this->crawlings->contains($crawling)) {
            $this->crawlings->add($crawling);
            $crawling->setServerInstance($this);
        }

        return $this;
    }

    public function removeCrawling(Crawling $crawling): static
    {
        if ($this->crawlings->removeElement($crawling)) {
            // set the owning side to null (unless already changed)
            if ($crawling->getServerInstance() === $this) {
                $crawling->setServerInstance(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }
}