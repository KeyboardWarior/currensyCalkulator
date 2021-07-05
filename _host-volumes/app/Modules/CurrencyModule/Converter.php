<?php


namespace app\Modules\CurrencyModule;

use app\models\CurrencyList;

class Converter
{
    /**
     * @param int $Value
     * @param string $CurrencyCode
     * @param string $ConvertCurrencyCode
     * @return float
     */
    public function convertCurrency(int $Value, string $CurrencyCode, string $ConvertCurrencyCode): float
    {
        $CurrencyData = $this->getCurrencyData($CurrencyCode);
        $ConvertCurrencyData = $this->getCurrencyData($ConvertCurrencyCode);

        if ($CurrencyCode != "RUB") {
            $ConvertValue = ($Value * ($CurrencyData["course"] * $CurrencyData["units"])) / $ConvertCurrencyData["course"];
        } else {
            $ConvertValue = $Value * $ConvertCurrencyData["course"] * $CurrencyData["units"];
        }

        return $ConvertValue;
    }

    /**
     * @param string $SymbolCode
     * @return array
     */
    public function getCurrencyData(string $SymbolCode): array
    {
        return CurrencyList::find()
            ->where(["symbol_code" => $SymbolCode])
            ->asArray()
            ->one();
    }
}