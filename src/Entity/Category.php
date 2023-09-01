<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ApiResource]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:collection:collection','read:user:item:me','read:user:item:home'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read:collection:collection','read:user:item:me','read:user:item:home'])]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: CollectionCard::class)]
    private Collection $collectionCards;

    public function __construct()
    {
        $this->collectionCards = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, CollectionCard>
     */
    public function getCollectionCards(): Collection
    {
        return $this->collectionCards;
    }

    public function addCollectionCard(CollectionCard $collectionCard): static
    {
        if (!$this->collectionCards->contains($collectionCard)) {
            $this->collectionCards->add($collectionCard);
            $collectionCard->setCategory($this);
        }

        return $this;
    }

    public function removeCollectionCard(CollectionCard $collectionCard): static
    {
        if ($this->collectionCards->removeElement($collectionCard)) {
            // set the owning side to null (unless already changed)
            if ($collectionCard->getCategory() === $this) {
                $collectionCard->setCategory(null);
            }
        }

        return $this;
    }
}
