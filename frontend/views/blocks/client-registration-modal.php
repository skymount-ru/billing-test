<?php

use common\models\Profile;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\bootstrap\Modal;

$model = new Profile(['scenario' => Profile::SCENARIO_CREATE_AJAX]);

Modal::begin([
    'id' => 'client-reg-modal',
    'header' => '<h2>Регистрация клиента</h2>',
]);
?>

    <?php $form = ActiveForm::begin([
            'action' => ['/profile/create'],
            'enableAjaxValidation' => true,
    ]); ?>

        <?= $form->field($model, 'l_name')->textInput(['placeholder' => 'Иванов'])->label('Фамилия') ?>
        <?= $form->field($model, 'f_name')->textInput(['placeholder' => 'Пётр'])->label('Имя') ?>
        <?= $form->field($model, 'm_name')->textInput(['placeholder' => 'Игнатьевич'])->label('Отчество') ?>
        <?= $form->field($model, 'phone')->textInput(['placeholder' => '+7 (912) 000-00-00'])->label('Номер сот. телефона') ?>

        <div class="form-group">
            <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-primary']) ?>
        </div>

    <?php ActiveForm::end(); ?>

<?php
Modal::end();
