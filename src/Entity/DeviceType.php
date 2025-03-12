<?php

namespace App\Entity;

use App\Repository\DeviceTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DeviceTypeRepository::class)]
class DeviceType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $DeviceName = null;

    #[ORM\Column]
    private ?string $DeviceId = null;

    #[ORM\Column(length: 255)]
    private ?string $NormalPrice = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $ImportedAt = null;

    /**
     * @var Collection<int, PurchaseHistory>
     */
    #[ORM\OneToMany(targetEntity: PurchaseHistory::class, mappedBy: 'SelectedDeviceType')]
    private Collection $purchaseHistories;

    public function __construct()
    {
        $this->purchaseHistories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeviceName(): ?string
    {
        return $this->DeviceName;
    }

    public function setDeviceName(string $DeviceName): static
    {
        $this->DeviceName = $DeviceName;

        return $this;
    }

    public function getDeviceId(): ?string
    {
        return $this->DeviceId;
    }

    public function setDeviceId(string $DeviceId): static
    {
        $this->DeviceId = $DeviceId;

        return $this;
    }

    public function getNormalPrice(): ?string
    {
        return $this->NormalPrice;
    }

    public function setNormalPrice(string $NormalPrice): static
    {
        $this->NormalPrice = $NormalPrice;

        return $this;
    }

    public function getImportedAt(): ?\DateTimeImmutable
    {
        return $this->ImportedAt;
    }

    public function setImportedAt(\DateTimeImmutable $ImportedAt): static
    {
        $this->ImportedAt = $ImportedAt;

        return $this;
    }

    /**
     * @return Collection<int, PurchaseHistory>
     */
    public function getPurchaseHistories(): Collection
    {
        return $this->purchaseHistories;
    }

    public function addPurchaseHistory(PurchaseHistory $purchaseHistory): static
    {
        if (!$this->purchaseHistories->contains($purchaseHistory)) {
            $this->purchaseHistories->add($purchaseHistory);
            $purchaseHistory->setSelectedDeviceType($this);
        }

        return $this;
    }

    public function removePurchaseHistory(PurchaseHistory $purchaseHistory): static
    {
        if ($this->purchaseHistories->removeElement($purchaseHistory)) {
            // set the owning side to null (unless already changed)
            if ($purchaseHistory->getSelectedDeviceType() === $this) {
                $purchaseHistory->setSelectedDeviceType(null);
            }
        }

        return $this;
    }
}
