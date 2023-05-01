<?php

namespace App\Entity;

use App\Repository\SongRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SongRepository::class)]
class Song
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[SerializedName('id')]
    #[Groups('song')]
    private ?int $id = null;

    #[Assert\NotBlank()]
    #[Assert\Length(min: 5, max: 255)]
    #[ORM\Column(length: 255)]
    #[SerializedName('title')]
    #[Groups('song')]
    private ?string $title = null;

    #[Assert\NotBlank()]
    #[Assert\Length(min: 5, max: 255)]
    #[ORM\Column(length: 255)]
    #[SerializedName('artist')]
    #[Groups('song')]
    private ?string $artist = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[SerializedName('cover')]
    #[Groups('song')]
    private ?string $cover = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[SerializedName('duration')]
    #[Groups('song')]
    private ?string $duration = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    private ?string $song = null;

    #[ORM\ManyToOne(targetEntity:User::class, inversedBy:"songs")]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private User|UserInterface|null $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getArtist(): ?string
    {
        return $this->artist;
    }

    public function setArtist(string $artist): self
    {
        $this->artist = $artist;

        return $this;
    }

    public function getCover(): ?string
    {
        return $this->cover;
    }

    public function setCover(?string $cover): self
    {
        $this->cover = $cover;

        return $this;
    }

    public function getSong(): ?string
    {
        return $this->song;
    }

    public function setSong(string $song): self
    {
        $this->song = $song;

        return $this;
    }

    public function getUser(): User|UserInterface|null
    {
        return $this->user;
    }

    public function setUser(User|UserInterface|null $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDuration(): ?string
    {
        return $this->duration;
    }

    /**
     * @param string|null $duration
     */
    public function setDuration(?string $duration): void
    {
        $this->duration = $duration;
    }
}
