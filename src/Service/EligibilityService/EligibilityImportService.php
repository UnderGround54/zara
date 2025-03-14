<?php
namespace App\Service\EligibilityService;

use App\Dto\EligibilityImportDto;
use App\Entity\Eligibility;
use App\Utils\ImportCsvUtil;
use App\Utils\ValidateData;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class EligibilityImportService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,

        #[Autowire(service: "monolog.logger.import")]
        private readonly LoggerInterface        $importLogger,
        private readonly ValidateData           $validateData,
        private readonly ImportCsvUtil          $importCsvUtil,
    ) {}

    /**
     * @param string $filePath
     * @return array
     */
    public function importCsv(string $filePath): array
    {
        $records = $this->importCsvUtil->readCsv($filePath, "|");
        $batchCount = 0;
        $batchSize = 500;
        $errors = [];

        $this->importLogger->info("Début import csv");

        foreach ($records as $index => $record) {
            try {
                $dto = $this->mapToDto($record);

                $validation = $this->validateData->validateDto($dto);

                if (!$validation instanceof EligibilityImportDto) {
                    $errors[$index] = $validation;
                    continue;
                }

                $eligibility = $this->mapToEntity($dto);

                $this->entityManager->persist($eligibility);
                $batchCount++;

                if ($batchCount % $batchSize === 0) {
                    $this->entityManager->flush();
                    $this->entityManager->clear();
                    $this->importLogger->info("Batch de $batchSize lignes inséré...");
                }
            } catch (\Exception $e) {
                $this->importLogger->error("Erreur à la ligne $index: " . json_encode($record) . " | " . $e->getMessage());
                $errors[$index] = ["Erreur inattendue" => $e->getMessage()];
            }
        }

        $this->entityManager->flush();
        $this->entityManager->clear();
        $this->importLogger->info("Import terminé");

        return $errors;
    }

    private function mapToDto(array $record): EligibilityImportDto
    {
        return new EligibilityImportDto(
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
            CreatedAt: \DateTimeImmutable::createFromFormat('d/m/Y H:i:s', $record['Created_at']),
            ImportedAt: new \DateTimeImmutable(),
            CIN: $record['CIN'] ?? null
        );
    }

    private function mapToEntity(EligibilityImportDto $dto): Eligibility
    {
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

        return $eligibility;
    }
}