<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model\MediaType;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\RequestBody;
use App\Controller\EligibilityCheckController;
use App\Controller\ImportCsvController;
use App\Dto\EligibilityRequestDto;
use App\Dto\EligibilityResponseDto;
use App\Provider\PaginationDataProvider;
use App\Repository\EligibilityRepository;
use ArrayObject;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\Entity(repositoryClass: EligibilityRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/eligibles',
            paginationEnabled: true,
            paginationItemsPerPage: 10,
            normalizationContext: ['groups' => ['list:read']],
            provider: PaginationDataProvider::class,
        ),
        new Post(
            uriTemplate: 'eligibles/verif-eligible',
            controller: EligibilityCheckController::class,
            normalizationContext: ['groups' => ['read']],
            denormalizationContext: ['groups' => ['write']],
            input: EligibilityRequestDto::class,
            output: EligibilityResponseDto::class,
            write: false
        ),
        new Post(
            uriTemplate: 'eligibles/import-csv',
            controller: ImportCsvController::class,
            openapi: new Operation(
                requestBody: new RequestBody(
                    content: new ArrayObject([
                        'multipart/form-data' => new MediaType(
                            schema: new ArrayObject([
                                'type' => 'object',
                                'properties' => [
                                    'file' => [
                                        'type' => 'string',
                                        'format' => 'binary',
                                    ],
                                ],
                            ])
                        ),
                    ])
                )
            )
        )
    ],
)]
#[ApiFilter(SearchFilter::class, properties: ['MSISDN' => 'ipartial', 'Nom' => 'ipartial'])]
class Eligibility
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[SerializedName("msisdn")]
    #[Groups(['list:read'])]
    private ?string $MSISDN = null;

    #[ORM\Column(length: 255)]
    #[Groups(['list:read'])]
    private ?string $Nom = null;

    #[ORM\Column(length: 255)]
    #[Groups(['list:read'])]
    private ?string $Prenom = null;

    #[ORM\Column(length: 255)]
    #[Groups(['list:read'])]
    private ?string $ARPU = null;

    #[ORM\Column]
    #[Groups(['list:read'])]
    private ?bool $IsSmartphone = null;

    #[ORM\Column(length: 255)]
    #[Groups(['list:read'])]
    private ?string $EligibleDevice = null;

    #[ORM\Column(length: 255)]
    #[Groups(['list:read'])]
    private ?string $CurrentNetwork = null;

    #[ORM\Column(length: 255)]
    #[Groups(['list:read'])]
    private ?string $DataActivity = null;

    #[ORM\Column(length: 255)]
    #[Groups(['list:read'])]
    private ?string $DiscountRate = null;

    #[ORM\Column(length: 255)]
    #[Groups(['list:read'])]
    private ?string $CIN = null;

    #[ORM\Column]
    #[Groups(['list:read'])]
    private ?bool $IsOldSim = null;

    #[ORM\Column]
    #[Groups(['list:read'])]
    private ?bool $IsPurchase = null;

    #[ORM\Column]
    #[Groups(['list:read'])]
    private ?\DateTimeImmutable $CreatedAt = null;

    #[ORM\Column]
    #[Groups(['list:read'])]
    private ?\DateTimeImmutable $ImportedAt = null;

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

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): static
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->Prenom;
    }

    public function setPrenom(string $Prenom): static
    {
        $this->Prenom = $Prenom;

        return $this;
    }

    public function getARPU(): ?string
    {
        return $this->ARPU;
    }

    public function setARPU(string $ARPU): static
    {
        $this->ARPU = $ARPU;

        return $this;
    }

    public function isSmartphone(): ?bool
    {
        return $this->IsSmartphone;
    }

    public function setIsSmartphone(bool $IsSmartphone): static
    {
        $this->IsSmartphone = $IsSmartphone;

        return $this;
    }

    public function getEligibleDevice(): ?string
    {
        return $this->EligibleDevice;
    }

    public function setEligibleDevice(string $EligibleDevice): static
    {
        $this->EligibleDevice = $EligibleDevice;

        return $this;
    }

    public function getCurrentNetwork(): ?string
    {
        return $this->CurrentNetwork;
    }

    public function setCurrentNetwork(string $CurrentNetwork): static
    {
        $this->CurrentNetwork = $CurrentNetwork;

        return $this;
    }

    public function getDataActivity(): ?string
    {
        return $this->DataActivity;
    }

    public function setDataActivity(string $DataActivity): static
    {
        $this->DataActivity = $DataActivity;

        return $this;
    }

    public function getDiscountRate(): ?string
    {
        return $this->DiscountRate;
    }

    public function setDiscountRate(string $DiscountRate): static
    {
        $this->DiscountRate = $DiscountRate;

        return $this;
    }

    public function getCIN(): ?string
    {
        return $this->CIN;
    }

    public function setCIN(string $CIN): static
    {
        $this->CIN = $CIN;

        return $this;
    }

    public function isOldSim(): ?bool
    {
        return $this->IsOldSim;
    }

    public function setIsOldSim(bool $IsOldSim): static
    {
        $this->IsOldSim = $IsOldSim;

        return $this;
    }

    public function isPurchase(): ?bool
    {
        return $this->IsPurchase;
    }

    public function setIsPurchase(bool $IsPurchase): static
    {
        $this->IsPurchase = $IsPurchase;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->CreatedAt;
    }

    public function setCreatedAt(\DateTimeImmutable $CreatedAt): static
    {
        $this->CreatedAt = $CreatedAt;

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
}
