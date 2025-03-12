<?php


namespace App\Utils;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidateData
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly ResponseUtil $responseUtil
    ){}

    /**
     * @param $dto
     * @return mixed
     */
    public function validateDto($dto): mixed
    {
        $errors = $this->validator->validate($dto);

        return $errors->count() > 0
            ? $this->responseUtil->error((array)$errors, $errors->get(0)->getMessage())
            : $dto;
    }
}
