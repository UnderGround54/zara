<?php
namespace App\Dto;

use Symfony\Component\Serializer\Annotation\Groups;

class EligibilityResponseDto
{
    #[Groups(['read'])]
    public string $msisdn;

    #[Groups(['read'])]
    public string $eligibilityStatus;

    #[Groups(['read'])]
    public string $message;

    #[Groups(['read'])]
    public ?int $discountRate = null;

    #[Groups(['read'])]
    public ?bool $oldSim = null;

    #[Groups(['read'])]
    public ?array $devicesList = null;

    public function __construct(
        string $msisdn = '',
        string $eligibilityStatus = '',
        string $message = '',
        ?int $discountRate = null,
        ?bool $oldSim = null,
        ?array $devicesList = null
    ) {
        $this->msisdn = $msisdn;
        $this->eligibilityStatus = $eligibilityStatus;
        $this->message = $message;
        $this->discountRate = $discountRate ?? null;
        $this->oldSim = $oldSim ?? null;
        $this->devicesList = $devicesList ?? [];
    }
}


