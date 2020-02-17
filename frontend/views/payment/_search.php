<?php

use kartik\daterange\DateRangePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\search\PaymentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payment-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <label>Инервал даты и времени</label>
    <?= DateRangePicker::widget([
        'name' => 'PaymentSearch[date_range]',
        'value' => $model->date_range,
        'convertFormat' => true,
        'pluginOptions' => [
            'timePicker' => true,
            'timePickerIncrement' => 15,
            'locale' => [
                'format'=>'d/m/Y H:i',
            ],
        ],
    ]) ?>
    <br>

    <?= $form->field($model, 'client_search')->label('Поиск профиля по UUID, либо телефону') ?>

    <div class="form-group">
        <?= Html::submitButton('Найти', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Сбросить', ['payment/index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
