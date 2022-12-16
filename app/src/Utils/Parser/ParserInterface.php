<?php

namespace App\Utils\Parser;

interface ParserInterface
{
    public function parse(string $fileName): array;
}