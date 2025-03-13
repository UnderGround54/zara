<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\EligibilityRequestDto;
use App\Service\EligibilityService\EligibilityService;
use App\Utils\ResponseUtil;
use App\Utils\SerializerUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class EligibilityCheckController extends AbstractController
{
    public function __construct(
        private readonly EligibilityService $eligibilityService,
        private readonly SerializerUtil     $serializerService,
        private readonly ResponseUtil       $responseUtil,
    ) {}

    public function __invoke(Request $request): Response
    {
        $requestDto = $this->serializerService->deserializeDataToDto($request->getContent(), EligibilityRequestDto::class, 'write');
        $res = $this->eligibilityService->checkEligibility($requestDto);

        return $this->responseUtil->success($res, null, Response::HTTP_OK);
    }
}
