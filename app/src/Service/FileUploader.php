<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    private string $targetDirectory;

    public function __construct(
        string                            $targetDirectory,
        private readonly SluggerInterface $slugger,
        private readonly Filesystem       $filesystem,
    )
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . '-' . uniqid('', true) . '.' . $file->guessExtension();

        $file->move($this->getTargetDirectory(), $fileName);

        return $fileName;
    }

    public function deleteFile(string $fileName): void
    {
        $this->filesystem->remove($this->getTargetDirectory() . '/' . $fileName);
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}