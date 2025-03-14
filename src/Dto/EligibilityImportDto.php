<?php
namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class EligibilityImportDto
{
    #[Assert\NotBlank(message: "Le MSISDN est requis.")]
    public string $MSISDN;

    #[Assert\NotBlank(message: "Le nom est requis.")]
    public string $Nom;

    #[Assert\NotBlank(message: "Le prénom est requis.")]
    public string $Prenom;

    #[Assert\NotBlank(message: "L'ARPU est requis.")]
    #[Assert\Type(type: 'float', message: "L'ARPU doit être un nombre.")]
    public float $ARPU;

    #[Assert\NotNull(message: "Le statut du smartphone est requis.")]
    public bool $IsSmartphone;

    #[Assert\NotBlank(message: "L'appareil éligible est requis.")]
    public string $EligibleDevice;

    #[Assert\NotBlank(message: "Le réseau actuel est requis.")]
    public string $CurrentNetwork;

    #[Assert\NotBlank(message: "L'activité des données est requise.")]
    public string $DataActivity;

    #[Assert\NotBlank(message: "Le taux de réduction est requis.")]
    #[Assert\Type(type: 'integer', message: "Le taux de réduction doit être un entier.")]
    public int $DiscountRate;

    #[Assert\NotNull(message: "L'ancienneté de la carte SIM est requise.")]
    public bool $IsOldSim;

    #[Assert\NotNull(message: "L'état d'achat est requis.")]
    public bool $IsPurchase;

    #[Assert\NotBlank(message: "La date de création est requise.")]
    #[Assert\Type(type: '\DateTimeImmutable', message: "Le format de la date de création est invalide.")]
    public \DateTimeImmutable $CreatedAt;

    public \DateTimeImmutable $ImportedAt;

    public ?string $CIN;

    public function __construct(
        string $MSISDN,
        string $Nom,
        string $Prenom,
        float $ARPU,
        bool $IsSmartphone,
        string $EligibleDevice,
        string $CurrentNetwork,
        string $DataActivity,
        int $DiscountRate,
        bool $IsOldSim,
        bool $IsPurchase,
        \DateTimeImmutable $CreatedAt,
        \DateTimeImmutable $ImportedAt,
        ?string $CIN = null
    ) {
        $this->MSISDN = $MSISDN;
        $this->Nom = $Nom;
        $this->Prenom = $Prenom;
        $this->ARPU = $ARPU;
        $this->IsSmartphone = $IsSmartphone;
        $this->EligibleDevice = $EligibleDevice;
        $this->CurrentNetwork = $CurrentNetwork;
        $this->DataActivity = $DataActivity;
        $this->DiscountRate = $DiscountRate;
        $this->IsOldSim = $IsOldSim;
        $this->IsPurchase = $IsPurchase;
        $this->CreatedAt = $CreatedAt;
        $this->ImportedAt = $ImportedAt;
        $this->CIN = $CIN;
    }
}
