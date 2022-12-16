<?php

namespace App\Utils\Parser;

use PhpOffice\PhpSpreadsheet\IOFactory;

class ParserXlsx implements ParserInterface
{
    public string $targetDirectory;

    public function __construct(string $targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function parse(string $fileName): array
    {
        $spreadsheet = IOFactory::load($this->targetDirectory . '/' . $fileName);
        $rawData = $spreadsheet->getActiveSheet()->toArray(
            null,
            true,
            true,
            true
        );

        $titles = array_values(array_shift($rawData));

        $result = array_map(static function ($row) use ($titles) {
            if (static::containsOnlyNull($row)) {
                return null;
            }
            $finalRow = array_combine((array)$titles, array_values($row));

            if(!empty($finalRow['full_name'])) {
                $finalRow = array_merge($finalRow, static::explodeFullName($finalRow['full_name']));
                unset($finalRow['full_name']);
            }

            return $finalRow;
        }, $rawData);

        return array_filter($result, static fn ($user) => !is_null($user));
    }

    private static function containsOnlyNull(array $array): bool
    {
        foreach ($array as $value) {
            if ($value !== null) {
                return false;
            }
        }
        return true;
    }

    private static function explodeFullName(string $fullName): array
    {
        $explode = explode(" ", $fullName);

        $partsOfFullName['first_name'] = $explode[0];
        $partsOfFullName['last_name'] = $explode[1];
        $partsOfFullName['middle_name'] = $explode[2];

        return $partsOfFullName;
    }
}