<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\DataPersister\PurchaseHistoryDataPersister;
use App\Dto\PurchaseHistoryRequestDto;
use App\Dto\PurchaseHistoryResponseDto;
use App\Provider\PaginationDataProvider;
use App\Repository\PurchaseHistoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PurchaseHistoryRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/histories',
            paginationEnabled: true,
            paginationItemsPerPage: 10,
            normalizationContext: ['groups' => ['list:read']],
            provider: PaginationDataProvider::class,
        ),
        new Post(
            uriTemplate: 'histories/notif-payment',
            input: PurchaseHistoryRequestDto::class,
            output: PurchaseHistoryResponseDto::class,
            write: true,
            processor: PurchaseHistoryDataPersister::class,
        ),
    ],
)]
#[ApiFilter(SearchFilter::class, properties: ['MSISDN' => 'ipartial', 'Nom' => 'ipartial'])]
class PurchaseHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['list:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 15)]
    #[Groups(['list:read'])]
    private ?string $MSISDN = null;

    #[ORM\Column(length: 255)]
    #[Groups(['list:read'])]
    private ?string $IDBillS3 = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['list:read'])]
    private ?string $IDPayment = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['list:read'])]
    private ?string $CIN = null;

    #[ORM\Column(length: 255)]
    #[Groups(['list:read'])]
    private ?string $SelectedDeviceID = null;

    #[ORM\Column]
    #[Groups(['list:read'])]
    private ?float $DiscountRate = null;

    #[ORM\Column(length: 255)]
    #[Groups(['list:read'])]
    private ?string $IMEI = null;

    #[ORM\Column(length: 255)]
    #[Groups(['list:read'])]
    private ?string $PaymentAmount = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['list:read'])]
    private ?\DateTimeImmutable $PaymentDate = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['list:read'])]
    private ?string $SelectedDeviceType = null;

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

    public function getSelectedDeviceType(): ?string
    {
        return $this->SelectedDeviceType;
    }

    public function setSelectedDeviceType(?string $SelectedDeviceType): static
    {
        $this->SelectedDeviceType = $SelectedDeviceType;

        return $this;
    }
}
