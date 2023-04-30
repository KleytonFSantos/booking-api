<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public int $id;

    #[ORM\Column(name: 'first_name', type: 'string', length: 255, nullable: false)]
    #[Assert\NotBlank(message: 'Preencha o campo primeiro nome.')]
    public ?string $firstName;

    #[ORM\Column(name: 'last_name', type: 'string', length: 255, nullable: false)]
    #[Assert\NotBlank(message: 'Preencha o campo primeiro last name.')]
    public ?string $lastName;

    #[ORM\Column(length: 180, unique: true, nullable: false)]
    #[Assert\NotBlank(message: 'Preencha o campo primeiro email.')]
    #[Assert\Email()]
    public string $email;

    #[ORM\Column]
    public array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column(nullable: false)]
    #[Assert\NotBlank(message: 'Preencha o campo primeiro password.')]
    public string $password;

     #[Assert\NotBlank()]
     #[Assert\Length(min:5, max:255)]
     #[Assert\EqualTo(propertyPath:"password", message:"The password is not the same as the password confirmation")]
     public string $password_confirmation;

     #[ORM\OneToMany(mappedBy: 'user_id', targetEntity: Song::class, orphanRemoval: true)]
     private Collection $songs;

     public function __construct()
     {
         $this->songs = new ArrayCollection();
     }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return User
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return $this->email;
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

    public function setRoles(array $roles): self
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

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getPasswordConfirmation(): string
    {
        return $this->password_confirmation;
    }

    /**
     * @param string $password_confirmation
     */
    public function setPasswordConfirmation(string $password_confirmation): void
    {
        $this->password_confirmation = $password_confirmation;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Song>
     */
    public function getSongs(): Collection
    {
        return $this->songs;
    }

    public function addSong(Song $song): self
    {
        if (!$this->songs->contains($song)) {
            $this->songs->add($song);
            $song->setUser($this);
        }

        return $this;
    }

    public function removeSong(Song $song): self
    {
        if ($this->songs->removeElement($song)) {
            // set the owning side to null (unless already changed)
            if ($song->getUser() === $this) {
                $song->setUser(null);
            }
        }

        return $this;
    }
}
