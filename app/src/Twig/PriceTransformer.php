<?php

namespace App\Twig;

use App\Utils\MoneyHelper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PriceTransformer extends AbstractExtension
{

    public function __construct(private readonly MoneyHelper $moneyHelper)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('price', [$this, 'formatPrice']),
        ];
    }

    public function formatPrice(int $number): string
    {
        return $this->moneyHelper::convert($number);
    }
}