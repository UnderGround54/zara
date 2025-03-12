<?php
namespace App\Dto;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class EligibilityRequestDto
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 10, max: 15)]
    #[Groups(['write'])]
    public string $msisdn;

    #[Assert\NotBlank]
    #[Assert\Choice(['s3', 'ussd'])]
    #[Groups(['write'])]
    public string $initiator;
}

