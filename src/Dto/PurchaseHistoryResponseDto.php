<?php
namespace App\Dto;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class PurchaseHistoryResponseDto
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 10, max: 15)]
    #[Groups(['read'])]
    public string $MSISDN;

    #[Assert\NotBlank]
    #[Groups(['read'])]
    public string $operation_status;

    #[Assert\NotBlank]
    #[Groups(['read'])]
    public string $status_reason;

    #[Assert\NotBlank]
    #[Groups(['read'])]
    public string $id_payment;

    public function __construct(
        string $MSISDN = '',
        string $operation_status = '',
        string $status_reason = '',
        string $id_payment = '',
    ) {
        $this->MSISDN = $MSISDN;
        $this->operation_status = $operation_status;
        $this->status_reason = $status_reason;
        $this->id_payment = $id_payment ?? null;
    }
}

