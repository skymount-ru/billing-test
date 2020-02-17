<?php

namespace frontend\controllers;

use common\models\Profile;
use Yii;
use common\models\Payment;
use frontend\models\search\PaymentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PaymentController implements the CRUD actions for Payment model.
 */
class PaymentController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Payment models.
     *
     * @param null|string $profile_uuid to show payments of.
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionIndex($profile_uuid = null)
    {
        /** @var Profile $profile */
        if ($profile_uuid !== null) {
            if (!($profile = Profile::findByUuid($profile_uuid))) {
                throw new NotFoundHttpException('Профиль клиента не найден.');
            }
            $profileId = $profile->id;
        } else {
            $profileId = null;
        }

        $searchModel = new PaymentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $profileId);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'profile' => @$profile,
        ]);
    }

    /**
     * Deletes payment by UUID.
     *
     * @param string $uuid
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete(string $uuid)
    {
        /** @var Payment $payment */
        if (!($payment = Payment::findOne(['uuid' => $uuid]))) {
            throw new NotFoundHttpException('Зачисление не найдено.');
        }

        $profile = $payment->profile;
        $payment->delete();
        $profile->refreshBalance();

        return $this->redirect(['index']);
    }
}
