<?php

namespace App\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\EligibilityRepository;
use App\Utils\ExportCsvUtil;

class ExportEligibilityCsvProvider implements ProviderInterface
{
    public function __construct(
        private readonly EligibilityRepository $repository,
        private readonly ExportCsvUtil $exportCsvUtil
    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $eligibilities = $this->repository->findAll();

        $data = array_map(fn($eligibility) => [
            $eligibility->getMSISDN(),
            $eligibility->getNom(),
            $eligibility->getPrenom(),
            $eligibility->getARPU(),
            $eligibility->isSmartphone() ? 'True' : 'False',
            $eligibility->getEligibleDevice(),
            $eligibility->getCurrentNetwork(),
            $eligibility->getDataActivity(),
            $eligibility->getDiscountRate(),
            $eligibility->getCIN(),
            $eligibility->isOldSim() ? 'True' : 'False',
            $eligibility->isPurchase() ? 'True' : 'False',
            $eligibility->getCreatedAt()->format('d/m/Y H:i:s'),
        ], $eligibilities);

        $headers = [
            'MSISDN', 'Nom', 'Prenom', 'ARPU', 'Is_Smartphone',
            'Eligible_Device', 'CurrentNetwork', 'Data_Activity',
            'Discount_Rate', 'CIN', 'Is_OldSIM', 'Is_Purchased', 'Created_at'
        ];
        $fileName = 'eligibles_export_'.date('Y-m-d');

        return $this->exportCsvUtil->export($fileName , $headers, $data, '|');
    }
}
