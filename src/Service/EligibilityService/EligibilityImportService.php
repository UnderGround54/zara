<?php
namespace App\Service\EligibilityService;

use App\Dto\EligibilityImportDto;
use App\Entity\Eligibility;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Exception;
use League\Csv\InvalidArgument;
use League\Csv\Reader;
use League\Csv\UnavailableStream;

class EligibilityImportService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {}

    /**
     * @throws InvalidArgument
     * @throws UnavailableStream
     * @throws Exception
     */
    public function importCsv(string $filePath): void
    {
        $csv = Reader::createFromPath($filePath, 'r');

        $csv->setDelimiter('|');
        $csv->setHeaderOffset(0);

        $records = $csv->getRecords();

        foreach ($records as $record) {
            $dto = new EligibilityImportDto(
                MSISDN: $record['MSISDN'],
                Nom: $record['Nom'],
                Prenom: $record['Prenom'],
                ARPU: (float) $record['ARPU'],
                IsSmartphone: filter_var($record['Is_Smartphone'], FILTER_VALIDATE_BOOLEAN),
                EligibleDevice: $record['Eligible_Device'],
                CurrentNetwork: $record['CurrentNetwork'],
                DataActivity: $record['Data_Activity'],
                DiscountRate: (int) $record['Discount_Rate'],
                IsOldSim: filter_var($record['Is_OldSIM'], FILTER_VALIDATE_BOOLEAN),
                IsPurchase: filter_var($record['Is_Purchased'], FILTER_VALIDATE_BOOLEAN),
                CreatedAt: \DateTimeImmutable::createFromFormat('d/m/Y H:i:s.u', $record['Created_at']),
                ImportedAt: new \DateTimeImmutable(),
                CIN: $record['CIN'] ?? null
            );

            $eligibility = new Eligibility();
            $eligibility->setMsisdn($dto->MSISDN);
            $eligibility->setNom($dto->Nom);
            $eligibility->setPrenom($dto->Prenom);
            $eligibility->setArpu($dto->ARPU);
            $eligibility->setIsSmartphone($dto->IsSmartphone);
            $eligibility->setEligibleDevice($dto->EligibleDevice);
            $eligibility->setCurrentNetwork($dto->CurrentNetwork);
            $eligibility->setDataActivity($dto->DataActivity);
            $eligibility->setDiscountRate($dto->DiscountRate);
            $eligibility->setCin($dto->CIN);
            $eligibility->setIsOldSim($dto->IsOldSim);
            $eligibility->setIsPurchase($dto->IsPurchase);
            $eligibility->setCreatedAt($dto->CreatedAt);
            $eligibility->setImportedAt($dto->ImportedAt);

            $this->entityManager->persist($eligibility);
        }

        $this->entityManager->flush();
    }
}