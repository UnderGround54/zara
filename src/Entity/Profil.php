<?php

namespace App\Entity;

use Amy\AccessRightBundle\Interface\CodeInterface;
use Amy\AccessRightBundle\Interface\StatusInterface;
use App\Repository\ProfilRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProfilRepository::class)]
class Profil implements CodeInterface, StatusInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $code = null;

    #[ORM\Column]
    private ?bool $status = null;

    /**
     * @var Collection<int, Right>
     */
    #[ORM\ManyToMany(targetEntity: Right::class, mappedBy: 'profils')]
    private Collection $rights;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'profils')]
    private Collection $users;

    /**
     * @var Collection<int, ProfilAuthorization>
     */
    #[ORM\OneToMany(targetEntity: ProfilAuthorization::class, mappedBy: 'profil', orphanRemoval: true)]
    private Collection $authorizations;

    public const DEFAULT_PROFIL = 'Guest';

    public function __construct()
    {
        $this->rights = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->authorizations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, Right>
     */
    public function getRights(): Collection
    {
        return $this->rights;
    }

    public function addRight(Right $right): static
    {
        if (!$this->rights->contains($right)) {
            $this->rights->add($right);
            $right->addProfil($this);
        }

        return $this;
    }

    public function removeRight(Right $right): static
    {
        if ($this->rights->removeElement($right)) {
            $right->removeProfil($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addProfil($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeProfil($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, ProfilAuthorization>
     */
    public function getAuthorizations(): Collection
    {
        return $this->authorizations;
    }

    public function addAuthorization(ProfilAuthorization $authorization): static
    {
        if (!$this->authorizations->contains($authorization)) {
            $this->authorizations->add($authorization);
            $authorization->setProfil($this);
        }

        return $this;
    }

    public function removeAuthorization(ProfilAuthorization $authorization): static
    {
        if ($this->authorizations->removeElement($authorization)) {
            // set the owning side to null (unless already changed)
            if ($authorization->getProfil() === $this) {
                $authorization->setProfil(null);
            }
        }

        return $this;
    }
}
