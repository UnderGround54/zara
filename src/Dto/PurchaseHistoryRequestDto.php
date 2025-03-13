<?php
namespace App\Dto;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class PurchaseHistoryRequestDto
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 10, max: 15)]
    #[Groups(['write'])]
    public string $MSISDN;

    #[Assert\NotBlank]
    #[Groups(['write'])]
    public string $CIN;

    #[Assert\NotBlank]
    #[Groups(['write'])]
    public string $id_payment;

    #[Assert\NotBlank]
    #[Groups(['write'])]
    public string $id_bill;

    #[Assert\NotBlank]
    #[Groups(['write'])]
    public string $selected_device;

    #[Assert\NotBlank]
    #[Groups(['write'])]
    public string $IMEI;

    #[Assert\NotBlank]
    #[Groups(['write'])]
    public string $amount;

    #[Assert\NotBlank]
    #[Groups(['write'])]
    public \DateTimeImmutable $date_payment;
}

