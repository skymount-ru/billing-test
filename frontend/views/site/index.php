<?php

/* @var $this yii\web\View */
/* @var $profilesDP yii\data\ActiveDataProvider */

$this->title = 'My Clients';

use yii\bootstrap4\Html;
use yii\grid\GridView; ?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Присоединяйтесь к нам</h1>

        <p class="lead">Быстрые и надежные платежи.</p>

        <p><a class="btn btn-lg btn-success" data-toggle="modal" data-target="#client-reg-modal">Пройти регистрацию</a></p>
    </div>

    <div class="body-content">

        <?= GridView::widget([
            'dataProvider' => $profilesDP,
            'columns' => [
                [
                    'attribute' => 'uuid',
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
                                return Html::a('<i class="fas fa-power-off"></i>', ['profile/status-inactivate', 'uuid' => $model->uuid], ['class' => 'btn btn-success', 'data-method' => 'post']);
                                break;
                            case \common\models\Profile::STATUS_INACTIVE:
                                return Html::a('<i class="fas fa-power-off"></i>', ['profile/status-activate', 'uuid' => $model->uuid], ['class' => 'btn btn-danger', 'data-method' => 'post']);
                                break;
                            default:
                                return Html::a('<i class="fas fa-power-off"></i>', ['profile/status-activate', 'uuid' => $model->uuid], ['class' => 'btn btn-warning', 'data-method' => 'post']);
                                break;
                        }
                    },
                ],
            ],
        ]) ?>

    </div>
</div>
