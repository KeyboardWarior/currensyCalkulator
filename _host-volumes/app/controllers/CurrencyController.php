<?php


namespace app\controllers;

use app\models\CurrencyForm;
use app\Modules\CurrencyModule\Converter;
use app\Modules\CurrencyModule\Service;
use Exception;
use Yii;
use yii\web\Controller;

class CurrencyController extends Controller
{
    public $Service;

    public function __construct($id, $module, $config = [])
    {
        $this->Service = new Service(new Converter());
        parent::__construct($id, $module, $config);
    }

    /**
     * @return string
     * @throws Exception
     */
    public function actionIndex(): string
    {
        $Model = new CurrencyForm();
        return $this->render('currency', [
            'CurrencyList' => $this->Service->getCurrencyList(),
            'CurrencyCodeList' => $this->Service->getCurrencyCodeList(),
            'Model' => $Model,
        ]);
    }

    /**
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionConvert(): string
    {
        $Model = new CurrencyForm();
        $PostData = Yii::$app->request->post();
        $ConvertedValue = 0;
        if ($Model->load($PostData) && $Model->validate()) {
            $ConvertData = $PostData['CurrencyForm'];
            $ConvertedValue = $this->Service->convertCurrency($ConvertData);
        }
        return $this->render('currency', [
            'CurrencyList' => $this->Service->getCurrencyList(),
            'CurrencyCodeList' => $this->Service->getCurrencyCodeList(),
            'ConvertedValue' => $ConvertedValue,
            'Model' => $Model,
        ]);

    }


}