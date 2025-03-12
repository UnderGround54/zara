<?php
namespace App\Dto;

readonly class EligibilityImportDto
{
    final public function __construct(
        public string $MSISDN,

        public string $Nom,

        public string $Prenom,

        public float $ARPU,

        public bool $IsSmartphone,

        public string $EligibleDevice,

        public string $CurrentNetwork,

        public string $DataActivity,

        public int $DiscountRate,

        public bool $IsOldSim,

        public bool $IsPurchase,

        public \DateTimeImmutable $CreatedAt,

        public \DateTimeImmutable $ImportedAt,

        public ?string $CIN = null,
    ) {}
}
