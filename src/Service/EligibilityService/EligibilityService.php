<?php

namespace App\Service\EligibilityService;

use App\Dto\DeviceTypeDto;
use App\Dto\EligibilityRequestDto;
use App\Dto\EligibilityResponseDto;
use App\Entity\DeviceType;
use App\Repository\EligibilityRepository;
use Doctrine\ORM\EntityManagerInterface;

class EligibilityService
{
    public function __construct(
        private readonly EligibilityRepository $eligibilityRepository,
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    public function checkEligibility(EligibilityRequestDto $requestDto): EligibilityResponseDto
    {
        $eligible = $this->eligibilityRepository->findOneBy(['MSISDN' => $requestDto->msisdn]);

        if (!$eligible) {
            return new EligibilityResponseDto(
                msisdn: $requestDto->msisdn,
                eligibilityStatus: 'NonEligible',
                message: 'MSISDN non trouvé',
                discountRate: null,
                oldSim: null,
                devicesList: []
            );
        }

        if ($eligible->IsPurchase()) {
            return new EligibilityResponseDto(
                msisdn: $requestDto->msisdn,
                eligibilityStatus: 'NonEligible',
                message: 'Offre déjà bénéficié par ce MSISDN',
                discountRate: null,
                oldSim: null,
                devicesList: []
            );
        }

        $devices = explode(',', $eligible->getEligibleDevice());
        $devicesList = [];
        foreach ($devices as $device) {
            $deviceType = $this->entityManager->getRepository(DeviceType::class)->findOneBy(['DeviceId' => trim($device)]);

            if ($deviceType) {
                $deviceTypeDto = new DeviceTypeDto(
                    deviceType_id: $deviceType->getDeviceId(),
                    deviceType_name: $deviceType->getDeviceName(),
                    discount_rate: $eligible->getDiscountRate(),
                    normal_price: $deviceType->getNormalPrice(),
                );
                $devicesList[] = $deviceTypeDto;
            }
        }


        return new EligibilityResponseDto(
            msisdn: $requestDto->msisdn,
            eligibilityStatus: 'Eligible',
            message: 'No error',
            discountRate: $eligible->getDiscountRate(),
            oldSim: $eligible->isOldSim(),
            devicesList: $devicesList
        );
    }
}
