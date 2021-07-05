<?php


namespace app\models;


use yii\base\Model;

class CurrencyForm extends Model
{
    public $Value;
    public $CurrencyCode;
    public $ConvertCurrencyCode;

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['Value', 'CurrencyCode', 'ConvertCurrencyCode'], 'required'],
            [['CurrencyCode', 'ConvertCurrencyCode'], 'string'],
            [['Value'], 'integer'],

        ];
    }
}