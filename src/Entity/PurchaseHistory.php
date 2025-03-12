<?php

namespace App\Entity;

use App\Repository\PurchaseHistoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PurchaseHistoryRepository::class)]
class PurchaseHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 15)]
    private ?string $MSISDN = null;

    #[ORM\Column(length: 255)]
    private ?string $IDBillS3 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $IDPayment = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $CIN = null;

    #[ORM\Column(length: 255)]
    private ?string $SelectedDeviceID = null;

    #[ORM\Column]
    private ?float $DiscountRate = null;

    #[ORM\Column(length: 255)]
    private ?string $IMEI = null;

    #[ORM\Column(length: 255)]
    private ?string $PaymentAmount = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $PaymentDate = null;

    #[ORM\ManyToOne(inversedBy: 'purchaseHistories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?DeviceType $SelectedDeviceType = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMSISDN(): ?string
    {
        return $this->MSISDN;
    }

    public function setMSISDN(string $MSISDN): static
    {
        $this->MSISDN = $MSISDN;

        return $this;
    }

    public function getIDBillS3(): ?string
    {
        return $this->IDBillS3;
    }

    public function setIDBillS3(string $IDBillS3): static
    {
        $this->IDBillS3 = $IDBillS3;

        return $this;
    }

    public function getIDPayment(): ?string
    {
        return $this->IDPayment;
    }

    public function setIDPayment(?string $IDPayment): static
    {
        $this->IDPayment = $IDPayment;

        return $this;
    }

    public function getCIN(): ?string
    {
        return $this->CIN;
    }

    public function setCIN(?string $CIN): static
    {
        $this->CIN = $CIN;

        return $this;
    }

    public function getSelectedDeviceID(): ?string
    {
        return $this->SelectedDeviceID;
    }

    public function setSelectedDeviceID(string $SelectedDeviceID): static
    {
        $this->SelectedDeviceID = $SelectedDeviceID;

        return $this;
    }

    public function getDiscountRate(): ?float
    {
        return $this->DiscountRate;
    }

    public function setDiscountRate(float $DiscountRate): static
    {
        $this->DiscountRate = $DiscountRate;

        return $this;
    }

    public function getIMEI(): ?string
    {
        return $this->IMEI;
    }

    public function setIMEI(string $IMEI): static
    {
        $this->IMEI = $IMEI;

        return $this;
    }

    public function getPaymentAmount(): ?string
    {
        return $this->PaymentAmount;
    }

    public function setPaymentAmount(string $PaymentAmount): static
    {
        $this->PaymentAmount = $PaymentAmount;

        return $this;
    }

    public function getPaymentDate(): ?\DateTimeImmutable
    {
        return $this->PaymentDate;
    }

    public function setPaymentDate(?\DateTimeImmutable $PaymentDate): static
    {
        $this->PaymentDate = $PaymentDate;

        return $this;
    }

    public function getSelectedDeviceType(): ?DeviceType
    {
        return $this->SelectedDeviceType;
    }

    public function setSelectedDeviceType(?DeviceType $SelectedDeviceType): static
    {
        $this->SelectedDeviceType = $SelectedDeviceType;

        return $this;
    }
}
