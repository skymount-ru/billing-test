<?php

/**
 * @var int $modalId
 */

use frontend\models\PaymentForm;
use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\web\JsExpression;

$model = new PaymentForm();

Modal::begin([
    'id' => $modalId,
    'header' => '<h2>Пополнить баланс</h2>',
]);
?>

<?php $form = ActiveForm::begin([
    'action' => ['/profile/deposit'],
]); ?>

    <?= $form->field($model, 'id')->widget(Select2::class, [
        'options' => [
            'placeholder' => 'Поиск по телефону или UUID ...'],
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 1,
            'language' => [
                'errorLoading' => new JsExpression("function () { return 'Ожидайте результата ...'; }"),
            ],
            'ajax' => [
                'url' => Url::to(['profile/uuid-list']),
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return { q:params.term }; }')
            ],
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            'templateResult' => new JsExpression('function (item) { return item.phone + "(" + item.l_name + ")"; }'),
            'templateSelection' => new JsExpression('function (item) { if (item.phone == undefined) { return item.text; } else { return item.phone + "(" + item.l_name + ")"; }}'),
        ],
    ])->label('Идентификатор клиента')->hint('Вы можете найти клиента по части его UUID, либо по его телефону.') ?>

    <?= $form->field($model, 'amount')->textInput(['type' => 'number'])->label('Сумма платежа, руб') ?>

    <div class="form-group">
        <?= Html::submitButton('Зачислить', ['class' => 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>


<?php
Modal::end();
