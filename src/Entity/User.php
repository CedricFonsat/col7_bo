<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\Api\User\DeleteUserController;
use App\Controller\Api\User\MeController;
use App\Controller\Api\User\RegisterController;
use App\Controller\Api\User\UsersHomeController;
use App\Controller\Api\User\UsersUploadImageController;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[Vich\Uploadable]
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/register',
            controller: RegisterController::class,
        ),
        new Post(
            uriTemplate: '/users/upload_image',
            controller: UsersUploadImageController::class,
        ),
        new Post(
            uriTemplate: '/users/{id}/delete',
            controller: DeleteUserController::class
        ),
        new Put(),
        new Get(
            uriTemplate: '/home/{id}',
            controller: UsersHomeController::class,
            normalizationContext: [
                'groups' => 'read:user:item:home',
            ]
        ),
        new Delete(),
        new GetCollection(),
        new GetCollection(
            uriTemplate: '/me',
            controller: MeController::class,
            normalizationContext: [
                'groups' => 'read:user:item:me',
            ]
        ),
  /*     new Post(
            uriTemplate: '/users/{id}/add_favorite/{cardId}',
            controller: AddFavoriteCardController::class
        ),
  */
    ],

)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:user:item:me','read:user:item:home','read:collection:collection'])]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['read:user:item:me','read:user:item:home'])]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups(['read:user:item:me','read:user:item:home'])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[Vich\UploadableField(mapping: 'user_thumbnail', fileNameProperty: 'imageName', size: 'imageSize')]
    #[Groups(['read:user:item:me'])]
    public ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['read:user:item:me','read:user:item:home'])]
    private ?string $imageName = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['read:user:item:me'])]
    private ?int $imageSize = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['read:user:item:me','read:user:item:home'])]
    private ?string $nickname = null;

    #[ORM\Column]
    #[Groups(['read:user:item:me','read:user:item:home'])]
    private ?int $wallet = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Card::class)]
    #[Groups(['read:user:item:me','read:user:item:home'])]
    private Collection $cards;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: CardFavoris::class)]
    #[Groups(['read:user:item:me','read:user:item:home'])]
    private Collection $cards_favoris;

    public function __construct()
    {
        $this->cards = new ArrayCollection();
        $this->wallet = 500;
        $this->roles = ['ROLE_USER'];
        $this->cards_favoris = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->email;
    }

    #[Groups(['read:user:item:me','read:user:item:home'])]
    public function getImageUrl(): string
    {
        if ($this->imageName != null){
            return 'http://192.168.1.14:8000/uploads/users/'.$this->imageName;
        }else{
            return 'https://4hcm.org/wp-content/uploads/2021/05/image-placeholder-350x350-1.png';
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = strtolower($email);

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): static
    {
        $this->nickname = strtolower($nickname);

        return $this;
    }

    public function getWallet(): ?int
    {
        return $this->wallet;
    }

    public function setWallet(int $wallet): static
    {
        $this->wallet = $wallet;

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
            $card->setUser($this);
        }

        return $this;
    }

    public function removeCard(Card $card): static
    {
        if ($this->cards->removeElement($card)) {
            // set the owning side to null (unless already changed)
            if ($card->getUser() === $this) {
                $card->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CardFavoris>
     */
    public function getCardsFavoris(): Collection
    {
        return $this->cards_favoris;
    }

    public function addCardsFavori(CardFavoris $cardsFavori): static
    {
        if (!$this->cards_favoris->contains($cardsFavori)) {
            $this->cards_favoris->add($cardsFavori);
            $cardsFavori->setUser($this);
        }

        return $this;
    }

    public function removeCardsFavori(CardFavoris $cardsFavori): static
    {
        if ($this->cards_favoris->removeElement($cardsFavori)) {
            // set the owning side to null (unless already changed)
            if ($cardsFavori->getUser() === $this) {
                $cardsFavori->setUser(null);
            }
        }

        return $this;
    }

}
