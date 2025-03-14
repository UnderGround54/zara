<?php

namespace App\Utils;

use League\Csv\Reader;
use Psr\Log\LoggerInterface;

class ImportCsvUtil
{
    public function __construct(private readonly LoggerInterface $logger) {}

    public function readCsv(string $filePath, $separator): iterable
    {
        try {
            $csv = Reader::createFromPath($filePath, 'r');
            $csv->setDelimiter($separator);
            $csv->setHeaderOffset(0);
            return $csv->getRecords();
        } catch (\Exception $e) {
            $this->logger->error("Erreur de lecture du fichier CSV: " . $e->getMessage());
            throw new \RuntimeException("Impossible de lire le fichier CSV.");
        }
    }
}
