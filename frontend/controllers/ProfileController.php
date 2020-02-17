<?php


namespace frontend\controllers;

use common\facade\UUID;
use common\facade\UUIDSchema;
use common\models\Profile;
use Yii;
use yii\bootstrap\Html;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;
use yii\widgets\ActiveForm;

class ProfileController extends \yii\web\Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
//            'access' => [
//                'class' => AccessControl::className(),
//                'only' => ['logout', 'signup'],
//                'rules' => [
//                    [
//                        'actions' => ['signup'],
//                        'allow' => true,
//                        'roles' => ['?'],
//                    ],
//                    [
//                        'actions' => ['logout'],
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ],
//                ],
//            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'create' => ['post'],
                    'status-activate' => ['post'],
                    'status-inactivate' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @return array|Response
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function actionCreate()
    {
        $profile = new Profile(['scenario' => Profile::SCENARIO_CREATE]);
        $profile->load(Yii::$app->request->post());

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $profile->scenario = Profile::SCENARIO_CREATE_AJAX;
            return ActiveForm::validate($profile);
        }

        if (UUID::fillModelWithValidUUID($profile, UUIDSchema::NS_PROFILE) && $profile->save()) {
            Yii::$app->session->setFlash('success', 'Thank you for registration.');
            return $this->goHome();
        }

        throw new \yii\web\UnprocessableEntityHttpException('Unable create a profile. ' . Html::errorSummary($profile));
    }

    public function actionStatusActivate(string $uuid)
    {
        try {
            Profile::setStatus($uuid, Profile::STATUS_ACTIVE);
            return $this->goHome();
        } catch (\Exception $e) {
            if ($e->getCode() == 404) {
                throw new NotFoundHttpException();
            }
            throw new ServerErrorHttpException();
        }
    }

    public function actionStatusInactivate(string $uuid)
    {
        try {
            Profile::setStatus($uuid, Profile::STATUS_INACTIVE);
            return $this->goHome();
        } catch (\Exception $e) {
            if ($e->getCode() == 404) {
                throw new NotFoundHttpException();
            }
            throw new ServerErrorHttpException();
        }
    }
}
