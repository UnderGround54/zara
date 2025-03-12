<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\FileUploadDto;
use App\Service\EligibilityService\EligibilityImportService;
use App\Utils\ResponseUtil;
use App\Utils\ValidateData;
use League\Csv\Exception;
use League\Csv\InvalidArgument;
use League\Csv\UnavailableStream;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ImportCsvController extends AbstractController
{
    public function __construct(
        private readonly EligibilityImportService $importService,
        private readonly ValidateData $validateData,
        private readonly ResponseUtil     $responseUtil,
    ) {}

    /**
     * @throws UnavailableStream
     * @throws InvalidArgument
     * @throws Exception
     */
    public function __invoke(Request $request): JsonResponse
    {
        $uploadedFile = $request->files->get('file');

        if (!$uploadedFile) {
            return new JsonResponse(['error' => 'Aucun fichier fourni.'], Response::HTTP_BAD_REQUEST);
        }

        $uploadedFileDto = new FileUploadDto($uploadedFile);
        $validated = $this->validateData->validateDto($uploadedFileDto);

        if (!$validated instanceof FileUploadDto) {
            return $validated;
        }
        $this->importService->importCsv($uploadedFile->getPathname());

        return $this->responseUtil->success('','Import terminé',Response::HTTP_CREATED);
    }
}
