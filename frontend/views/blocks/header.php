<?php

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;

$regModalId = 'client-reg-modal';

NavBar::begin([
    'brandLabel' => Yii::$app->name,
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar-inverse navbar-fixed-top',
    ],
]);
$menuItems = [
    ['label' => 'Главная', 'url' => ['/site/index']],
    [
        'label' => 'Регистрация клиента',
        'options' => ['data-toggle' => 'modal', 'data-target' => "#{$regModalId}", 'style' => 'cursor: pointer;'],
        'url' => false,
    ],
];
if (Yii::$app->user->isGuest) {
    $menuItems[] = ['label' => 'Регистрация пользователя', 'url' => ['/site/signup']];
    $menuItems[] = ['label' => 'Вход для пользователя', 'url' => ['/site/login']];
} else {
    $menuItems[] = '<li>'
        . Html::beginForm(['/site/logout'], 'post')
        . Html::submitButton(
            'Выйти (' . Yii::$app->user->identity->username . ')',
            ['class' => 'btn btn-link logout']
        )
        . Html::endForm()
        . '</li>';
}
echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => $menuItems,
]);
NavBar::end();
?>

<?= $this->render('//blocks/client-registration-modal', [
    'modalId' => $regModalId,
]) ?>
