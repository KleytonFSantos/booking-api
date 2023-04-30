<?php

namespace App\Entity;

use App\Repository\UserProfileRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserProfileRepository::class)]
class UserProfile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Preencha o campo primeiro description.')]
    #[Assert\Length(min: 5, max: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $avatar = null;

    #[Assert\NotBlank(message: 'Preencha o campo primeiro cidade.')]
    #[Assert\Length(min: 5, max: 255)]
    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[Assert\NotBlank(message: 'Preencha o campo primeiro estado.')]
    #[Assert\Length(min: 5, max: 255)]
    #[ORM\Column(length: 255)]
    private ?string $state = null;

    #[ORM\OneToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private ?User $user = null;

    /**
     * @return User|UserInterface|null
     */
    public function getUser(): User|UserInterface|null
    {
        return $this->user;
    }

    /**
     * @param User|UserInterface|null $user
     */
    public function setUser(User|UserInterface|null $user): void
    {
        $this->user = $user;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function setAvatarFile(?\Symfony\Component\HttpFoundation\File\UploadedFile $file): self
    {
        if ($file !== null) {
            $this->avatar = $file->getClientOriginalName();
        }

        return $this;
    }
}
