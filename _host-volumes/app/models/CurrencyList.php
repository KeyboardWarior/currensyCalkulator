<?php


namespace app\models;

use yii\db\ActiveRecord;

class CurrencyList extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'currency';
    }

    public function rules()
    {
        return [
            [['currency','symbol_code','coefficient'],'string'],
            [['code','units'],'integer'],
            [['course'],'float'],

        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'currency' => 'Currency',
            'symbol_code' => 'Symbol code',
            'code' => 'Code',
            'coefficient' => 'Coefficient',
            'course' => 'Course',
        ];
    }
}