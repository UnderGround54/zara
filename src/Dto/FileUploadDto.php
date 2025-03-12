<?php
namespace App\Dto;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

readonly class FileUploadDto
{
    final public function __construct(
        #[Assert\NotNull(message: "Le fichier est obligatoire")]
        #[Assert\File(
            maxSize: "50M",
            mimeTypes: ["text/csv", "text/plain"],
            mimeTypesMessage: "Veuillez télécharger un fichier CSV valide."
        )]
        public UploadedFile $file
    ) {}
}