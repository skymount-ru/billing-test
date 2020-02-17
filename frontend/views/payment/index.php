<?php

use common\models\Profile;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\search\PaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $profile Profile */

$this->title = 'Зачисления';
if ($profile) {
    $this->params['breadcrumbs'][] = ['label' => 'Все зачисления', 'url' => ['payment/index']];
    $this->title .= " для \"{$profile->f_name}\"";
}
$this->params['breadcrumbs'][] = $this->title;

$sumQuery = clone $dataProvider->query;
$sum = $sumQuery->sum('amount');

?>
<div class="payment-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_search', ['model' => $searchModel]); ?>
    <hr>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'showFooter' => true,
        'columns' => [
            'id',
            [
                'attribute' => 'created_at',
                'value' => function ($model) {
                    return date('d/m/Y H:i', $model->created_at);
                },
            ],
            [
                'attribute' => 'profile_id',
                'format' => 'raw',
                'value' => function($model) {
                    return Html::tag('div', $model->profile->getFio(), ['title' => 'Тел. ' . $model->profile->phone]);
                },
                'footer' => 'Итого:',
            ],
            [
                'attribute' => 'amount',
                'value' => function($model) {
                    return Yii::$app->formatter->asCurrency($model->amount);
                },
                'footer' => $sum ? Yii::$app->formatter->asCurrency($sum) : '—',
            ],
            [
                'label' => 'Действие',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a(
                        '<i class="fas fa-trash"></i> Отменить',
                        ['payment/delete', 'uuid' => $model->uuid],
                        ['class' => 'btn btn-sm btn-danger', 'data-method' => 'post']
                    );
                },
            ],
        ],
    ]); ?>
</div>
