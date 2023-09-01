<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\Api\Collection\ComingOutSoonCollectionCardController;
use App\Repository\CollectionCardRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: CollectionCardRepository::class)]
#[ApiResource(
    operations: [
        new Post(),
        new Get(),
        new Put(),
        new Delete(),
        new GetCollection(),
        new GetCollection(
            uriTemplate: '/collection_card/coming_out_soon',
            controller: ComingOutSoonCollectionCardController::class
        )
    ],
    normalizationContext: ['groups' => ['read:collection:collection']],
)]
#[Vich\Uploadable]
class CollectionCard
{
    const CATEGORY_FOOTBALL = 'https://nftlately.com/wp-content/uploads/2022/12/AFA-X-Upland.jpeg';
    const CATEGORY_METAVERSE = 'https://www.cnet.com/a/img/resize/0f0a5f4a39fe758f422270e51465271682ba7a6a/hub/2022/04/13/2b185b38-a6b1-4056-9a5c-5a3db0806617/maxresdefault.jpg?auto=webp&fit=crop&height=675&width=1200';
    const CATEGORY_AUTRE = 'https://hellotoken.io/wp-content/uploads/2021/12/piano-king-nft-scaled.jpg';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:collection:collection','read:user:item:me','read:user:item:home'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read:collection:collection','read:user:item:me','read:user:item:home'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['read:collection:collection','read:user:item:me','read:user:item:home'])]
    private ?string $author = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['read:collection:collection','read:user:item:me','read:user:item:home'])]
    private ?string $description = null;

    #[Vich\UploadableField(mapping: 'collection_thumbnail', fileNameProperty: 'imageName', size: 'imageSize')]
    #[Groups(['read:collection:collection'])]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['read:collection:collection','read:user:item:me','read:user:item:home'])]
    private ?string $imageName = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['read:collection:collection'])]
    private ?int $imageSize = null;

    #[ORM\ManyToOne(inversedBy: 'collectionCards')]
    #[Groups(['read:collection:collection','read:user:item:me','read:user:item:home'])]
    private ?Category $category = null;

    #[ORM\OneToMany(mappedBy: 'collection', targetEntity: Card::class)]
    #[Groups(['read:collection:collection'])]
    private Collection $cards;

    #[ORM\Column]
    private ?bool $isComingSoon = null;

    public function __construct()
    {
        $this->cards = new ArrayCollection();
        $this->isComingSoon = false;
    }

    public static function getCoverToEventComingSoon()
    {
        return 'https://i0.wp.com/blog.okcoin.com/wp-content/uploads/2022/04/20220419-160132.png?fit=1600%2C844&ssl=1';
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

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(?string $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, Card>
     */
    public function getCards(): Collection
    {
        return $this->cards;
    }

    public function addCard(Card $card): static
    {
        if (!$this->cards->contains($card)) {
            $this->cards->add($card);
            $card->setCollection($this);
        }

        return $this;
    }

    public function removeCard(Card $card): static
    {
        if ($this->cards->removeElement($card)) {
            // set the owning side to null (unless already changed)
            if ($card->getCollection() === $this) {
                $card->setCollection(null);
            }
        }

        return $this;
    }

    public function isIsComingSoon(): ?bool
    {
        return $this->isComingSoon;
    }

    public function setIsComingSoon(bool $isComingSoon): static
    {
        $this->isComingSoon = $isComingSoon;

        return $this;
    }
}
