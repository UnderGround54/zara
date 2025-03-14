<?php


namespace App\Utils;

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

        if ($errors->count() > 0) {
            $errorMessages = [];

            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }

            return $this->responseUtil->error($errorMessages, "Validation failed");
        }

        return $dto;
    }

}
