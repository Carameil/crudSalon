<?php

namespace App\UseCase\Admin\Import;

use App\Entity\Material;
use App\Entity\Service;
use App\Repository\CategoryRepository;
use App\Repository\MaterialRepository;
use App\Repository\PositionRepository;
use App\Repository\ServiceRepository;
use App\Service\Doctrine\Flusher;
use App\Service\FileUploader;
use App\Utils\Parser\ParserInterface;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

readonly class MaterialHandler
{
    public function __construct(
        private FileUploader           $fileUploader,
        private ParserInterface        $parser,
        private Flusher                $flusher,
        private EntityManagerInterface $entityManager,
        private MaterialRepository     $materialRepository,
    )
    {
    }

    /**
     * @throws Exception
     * @throws EntityNotFoundException
     */
    public function handle(Command $command)
    {
        $materialsName = $this->fileUploader->upload($command->uploadFile);
        $sheetData = $this->parser->parse($materialsName);

        $this->entityManager->getConnection()->beginTransaction();
        try {
            foreach ($sheetData as $row) {
                $material = Material::create(
                    $row['name'],
                    $row['manufacturer'],
                    $row['supplier'],
                    $row['quantity'],
                    $row['unit'],
                    $row['description'],
                );

                $this->materialRepository->save($material);
            }
            $this->flusher->flush();
            $this->entityManager->commit();
        } catch (Exception $exception) {
            $this->entityManager->rollback();
            throw $exception;
        }
        $this->fileUploader->deleteFile($materialsName);

        return true;
    }
}