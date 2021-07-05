<?php

/* @var $this yii\web\View
 * @var $CurrencyList
 * @var $CurrencyCodeList
 * @var $ConvertedValue
 * @var $Model
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

$this->title = 'Currency List';
?>
<table class="table">
    <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">NumCode</th>
            <th scope="col">CharCode</th>
            <th scope="col">Nominal</th>
            <th scope="col">Name</th>
            <th scope="col">Value</th>
            <th scope="col">coefficient</th>
        </tr>
    </thead>
    <tbody>
<?php
foreach ($CurrencyList as $value){?>
        <tr>
            <td><?php print_r($value['id']);?></td> <?php
            ?><td><?php print_r($value['code']);?></td> <?php
            ?><td><?php print_r($value['symbol_code']);?></td> <?php
            ?><td><?php print_r($value['units']);?></td> <?php
            ?><td><?php print_r($value['currency']);?></td> <?php
            ?><td><?php print_r($value['course']);?></td> <?php
            ?><td><?php print_r($value['coefficient']);?></td>
        </tr>
    <?php }?>
    </tbody>
</table>
<?php


Pjax::begin(['id' => 'currency-form']);
    $Form = ActiveForm::begin([
        'id' => 'currency-form',
        'options' => ['data-pjax' => true],
        'action' => ['currency/convert'],
    ]);
        echo $Form->field($Model, 'Value')->input('string');
        echo $Form->field($Model, 'CurrencyCode')->dropDownList($CurrencyCodeList);
        echo $Form->field($Model, 'ConvertCurrencyCode')->dropDownList($CurrencyCodeList);
        echo Html::submitButton('Конвертировать', ['class' => 'btn btn-primary']);
        if ($ConvertedValue) {
            echo '<h2> Результат ' . $ConvertedValue . '</h2>';
        }
    ActiveForm::end();
Pjax::end();

?>

