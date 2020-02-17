<?php

/* @var $this yii\web\View */
/* @var $profilesDP yii\data\ActiveDataProvider */

use yii\bootstrap\Html;
use yii\grid\GridView;
use yii\helpers\Url;

$this->title = 'My Clients';
$paymentModalId = 'payment-modal';

?>
<div class="site-index">
    <div class="jumbotron">
        <h1>Присоединяйтесь к нам</h1>
        <p class="lead">Быстрые и надежные платежи.</p>
        <p><a class="btn btn-success" data-toggle="modal" data-target="#client-reg-modal">Пройти регистрацию</a></p>
    </div>
    <div class="body-content">
        <?= GridView::widget([
            'dataProvider' => $profilesDP,
            'columns' => [
                [
                    'attribute' => 'uuid',
                    'format' => 'raw',
                    'value' => function($model) {
                        return Html::a($model->uuid, ['payment/index', 'profile_uuid' => $model->uuid]);
                    },
                ],
                [
                    'attribute' => 'phone',
                ],
                [
                    'attribute' => 'l_name',
                    'label' => 'ФИО',
                    'value' => function ($model) {
                        return $model->getFio();
                    },
                ],
                'balance:currency',
                [
                    'attribute' => 'status',
                    'format' => 'raw',
                    'value' => function ($model) {
                        switch ($model->status) {
                            case \common\models\Profile::STATUS_ACTIVE:
                                $linkClass = 'btn-success';
                                $link = ['profile/status-inactivate'];
                                $label = 'Активен';
                                break;
                            case \common\models\Profile::STATUS_INACTIVE:
                                $linkClass = 'btn-danger';
                                $link = ['profile/status-activate'];
                                $label = 'Неактивен';
                                break;
                            default:
                                $linkClass = 'btn-warning';
                                $link = ['profile/status-activate'];
                                $label = 'Новый';
                                break;
                        }
                        return Html::a(
                            '<i class="fas fa-power-off"></i> ' . $label,
                            $link + ['uuid' => $model->uuid],
                            ['class' => 'btn btn-sm ' . $linkClass, 'data-method' => 'post']
                        );
                    },
                ],
            ],
        ]) ?>
        <hr>
        <button class="btn btn-primary" data-toggle="modal" data-target="#<?= $paymentModalId ?>">Пополнить баланс</button>
        <a href="<?= Url::to(['payment/index']) ?>" class="btn btn-info">Все зачисления</a>
    </div>
</div>

<?= $this->render('//blocks/payment-modal', [
    'modalId' => $paymentModalId,
]) ?>
