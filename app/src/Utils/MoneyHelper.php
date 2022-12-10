<?php

namespace App\Utils;

use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;

class MoneyHelper
{
    public static function convert(int $value): bool|string
    {
        $money = new Money($value, new Currency('RUB'));
        $currencies = new ISOCurrencies();

        $numberFormatter = new \NumberFormatter('ru_RUS', \NumberFormatter::CURRENCY);
        $moneyFormatter = new IntlMoneyFormatter($numberFormatter, $currencies);

        return $moneyFormatter->format($money);
    }
}