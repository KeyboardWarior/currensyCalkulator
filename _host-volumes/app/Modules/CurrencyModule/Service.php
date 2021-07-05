<?php

namespace app\Modules\CurrencyModule;

use app\models\CurrencyList;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

class Service
{
    /**
     * @var int Интервал в часах на обновление данных о валютах
     */
    private $ChangeDataInterval = 24;

    public $Converter;
    /**
     * Адрес сервиса для получения данных о валютах
     */
    const DATA_URL = 'https://www.cbr-xml-daily.ru/daily_json.js';

    /**
     * Service constructor.
     * @param Converter $Converter
     */
    public function __construct(Converter $Converter)
    {
        $this->Converter = $Converter;
    }

    /**
     * @return array|ActiveRecord[]
     * @throws Exception
     * @throws \Exception
     */
    public function getCurrencyList(): array
    {
        $CurrencyList = CurrencyList::find()->asArray()->all();
        if (empty($CurrencyList)) {
            $CurrencyList = $this->getCurrencyListFromService();
            $this->saveCurrencyData($CurrencyList);
        }
        if ($this->checkUpdateDate($CurrencyList)) {
            $this->updateCurrencyData();
        }
        return $CurrencyList;
    }

    /**
     * @return array
     */
    public function getCurrencyCodeList(): array
    {
        $CurrencyList = CurrencyList::find()->asArray()->all();
        $CurrencyCodeList = [];
        //@todo arrayhelper
        foreach ($CurrencyList as $Currency) {
            $CurrencyCodeList[$Currency['symbol_code']] = $Currency['currency'];
        }
        return $CurrencyCodeList;

    }

    /**
     * @param array $CurrencyList
     * @return bool
     * @throws \Exception
     */
    public function checkUpdateDate(array $CurrencyList): bool
    {
        //Получаем дату обновления
        $LastUpdateDate = ArrayHelper::getValue($CurrencyList[0], 'date', '');
        $UpdateDate = date("Y-m-d H:i:s", strtotime($LastUpdateDate . "$this->ChangeDataInterval hours"));
        $CurrentDate = date("Y-m-d H:i:s");
        return $UpdateDate < $CurrentDate;
    }

    /**
     * @return array
     */
    public function getCurrencyListFromService(): array
    {
        $InputData = json_decode(file_get_contents(self::DATA_URL));
        $CurrencyList = [];
        if (!empty($InputData)) {
            foreach ($InputData->Valute as $key => $value) {
                $CurrencyList[$key]['code'] = $value->NumCode;
                $CurrencyList[$key]['symbol_code'] = $value->CharCode;
                $CurrencyList[$key]['units'] = $value->Nominal;
                $CurrencyList[$key]['currency'] = $value->Name;
                $CurrencyList[$key]['course'] = $value->Value;
                $CurrencyList[$key]['coefficient'] = $this->getTrend($value->Value, $value->Previous);
                $CurrencyList[$key]['date'] = date("Y-m-d H:i:s");
            }
        }
        return $CurrencyList;
    }

    /**
     * @param string $CurrentValue
     * @param string $PreviousValue
     * @return string
     */
    public function getTrend(string $CurrentValue, string $PreviousValue): string
    {
        $Trend = '';
        if ($CurrentValue < $PreviousValue) {
            $Trend = ' ▲';
        } elseif ($CurrentValue > $PreviousValue) {
            $Trend = ' ▼';
        }
        return $Trend;
    }

    /**
     * @param array $CurrencyList
     * @throws Exception
     */
    public function saveCurrencyData(array $CurrencyList)
    {
        Yii::$app->db->createCommand()->batchInsert('currency', [
            'code',
            'symbol_code',
            'units',
            'currency',
            'course',
            'coefficient',
            'date'
        ], $CurrencyList)->execute();

    }

    /**
     * @throws Exception
     */
    public function updateCurrencyData()
    {
        CurrencyList::deleteAll();
        $this->saveCurrencyData($this->getCurrencyListFromService());
    }

    /**
     * @param $ConvertData
     * @return float
     */
    public function convertCurrency($ConvertData): float
    {
        return $this->Converter->convertCurrency($ConvertData['Value'],
            $ConvertData['CurrencyCode'],
            $ConvertData['ConvertCurrencyCode']);
    }


}