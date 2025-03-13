<?php
namespace App\DataPersister;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\PurchaseHistoryRequestDto;
use App\Dto\PurchaseHistoryResponseDto;
use App\Entity\DeviceType;
use App\Entity\Eligibility;
use App\Entity\PurchaseHistory;
use App\Utils\ResponseUtil;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

final class PurchaseHistoryDataPersister implements ProcessorInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ResponseUtil $responseUtil
    ) {}

    public function supports($data): bool
    {
        return $data instanceof PurchaseHistoryRequestDto;
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = []): JsonResponse
    {


        if (!$data instanceof PurchaseHistoryRequestDto) {
            $requestDto = $this->responseDto($data, 'Invalid data provided','failed');

            return $this->responseUtil->error((array)$requestDto);
        }

        $eligibility = $this->entityManager->getRepository(Eligibility::class)->findOneBy(['MSISDN' => $data->MSISDN]);

        if (!$eligibility) {
            $requestDto = $this->responseDto($data, 'Client non éligible','failed');

            return $this->responseUtil->error((array)$requestDto);
        } else {
            $deviceType = $this->entityManager->getRepository(DeviceType::class)->findOneBy(['DeviceId' => $data->selected_device]);
            if (!$deviceType) {
                $requestDto = $this->responseDto($data, 'Device non trouvée','failed');

                return $this->responseUtil->error((array)$requestDto);
            }
            $deviceIds   = explode(',', $eligibility->getEligibleDevice());
            $deviceExist = in_array($data->selected_device, $deviceIds);
            if (!$deviceExist) {
                $requestDto = $this->responseDto($data, 'Device non trouvée dans la base eligible de ce client','failed');

                return $this->responseUtil->error((array)$requestDto);
            }
        }


        $purchaseHistory = new PurchaseHistory();

        $purchaseHistory->setCIN($eligibility->getCIN());
        $purchaseHistory->setMSISDN($eligibility->getMSISDN());
        $purchaseHistory->setIDPayment($data->id_payment);
        $purchaseHistory->setSelectedDeviceID($deviceType->getDeviceId());
        $purchaseHistory->setSelectedDeviceType($deviceType->getDeviceName());
        $purchaseHistory->setIMEI($data->IMEI);
        $purchaseHistory->setDiscountRate($eligibility->getDiscountRate());
        $purchaseHistory->setIDBillS3($data->id_bill);
        $purchaseHistory->setPaymentAmount($data->amount);
        $purchaseHistory->setPaymentDate(new \DateTimeImmutable());

        $eligibility->setIsPurchase(true);

        $this->entityManager->persist($purchaseHistory);
        $this->entityManager->persist($eligibility);
        $this->entityManager->flush();

        $requestDto = $this->responseDto($data);

        return $this->responseUtil->success($requestDto);
    }

    /**
     * @param $data
     * @param string $message
     * @param string $statusMessage
     * @return PurchaseHistoryResponseDto
     */
    private function responseDto($data, string $message = "Operation OK", string $statusMessage = 'success'): PurchaseHistoryResponseDto
    {
        return new PurchaseHistoryResponseDto(
            MSISDN: $data->MSISDN,
            operation_status: $statusMessage,
            status_reason: $message,
            id_payment: $data->id_payment,
        );
    }

    public function remove($data): void
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}