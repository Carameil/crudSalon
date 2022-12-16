<?php

namespace App\UseCase\Admin\Import;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    #[Assert\NotBlank]
    public UploadedFile $uploadFile;
}