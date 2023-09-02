<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Controller\Api\Card\BuyCardController;
use App\Repository\CardRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: CardRepository::class)]
#[Vich\Uploadable]
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/buy/{id}',
            controller: BuyCardController::class
        ),
        new Get(),
        new GetCollection()
    ]
)]
class Card
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([
        'read:collection:collection',
        'read:user:item:me',
        'read:user:item:home'
    ])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups([
        'read:collection:collection',
        'read:user:item:me',
        'read:user:item:home'
    ])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups([
        'read:collection:collection',
        'read:user:item:me',
        'read:user:item:home'
    ])]
    private ?int $price = null;

    #[ORM\Column]
    #[Groups([
        'read:collection:collection',
        'read:user:item:me',
        'read:user:item:home'
    ])]
    private ?bool $ifAvailable = null;

    #[Vich\UploadableField(mapping: 'card_thumbnail', fileNameProperty: 'imageName', size: 'imageSize')]
    #[Groups([
        'read:collection:collection',
        'read:user:item:me',
    ])]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    #[Groups([
        'read:collection:collection',
        'read:user:item:me',
        'read:user:item:home'
    ])]
    private ?string $imageName = null;

    #[ORM\Column(nullable: true)]
    #[Groups([
        'read:collection:collection',
        'read:user:item:me'
    ])]
    private ?int $imageSize = null;

    #[ORM\ManyToOne(inversedBy: 'cards')]
    #[Groups(['read:user:item:me','read:user:item:home'])]
    private ?CollectionCard $collection = null;

    #[ORM\ManyToOne(cascade: ['remove', 'persist'], inversedBy: 'cards')]
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: CardFavoris::class, mappedBy: 'cards')]
    private Collection $cardFavoris;

    public function __construct()
    {
        $this->user = null;
        $this->cardFavoris = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name;
    }

    #[Groups(['read:user:item:me', 'read:user:item:home'])]
    public function getImageUrl(): string
    {
        if ($this->imageName != null) {
            return 'http://192.168.1.123:8000/uploads/cards/' . $this->imageName;
        } else {
            return 'https://4hcm.org/wp-content/uploads/2021/05/image-placeholder-350x350-1.png';
        }
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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function isIfAvailable(): ?bool
    {
        return $this->ifAvailable;
    }

    public function setIfAvailable(bool $ifAvailable): static
    {
        $this->ifAvailable = $ifAvailable;

        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }

    public function setImageSize(?int $imageSize): void
    {
        $this->imageSize = $imageSize;
    }

    public function getCollection(): ?CollectionCard
    {
        return $this->collection;
    }

    public function setCollection(?CollectionCard $collection): static
    {
        $this->collection = $collection;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, CardFavoris>
     */
    public function getCardFavoris(): Collection
    {
        return $this->cardFavoris;
    }

    public function addCardFavori(CardFavoris $cardFavori): static
    {
        if (!$this->cardFavoris->contains($cardFavori)) {
            $this->cardFavoris->add($cardFavori);
            $cardFavori->addCard($this);
        }

        return $this;
    }

    public function removeCardFavori(CardFavoris $cardFavori): static
    {
        if ($this->cardFavoris->removeElement($cardFavori)) {
            $cardFavori->removeCard($this);
        }

        return $this;
    }
}
