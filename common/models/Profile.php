<?php


namespace common\models;

use common\models\db\BaseProfile;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Html;

class Profile extends BaseProfile
{
    const STATUS_INACTIVE = 5;
    const STATUS_ACTIVE = 10;

    const SCENARIO_CREATE = 'create';
    /**
     * Special sub-scenario for ajax validation to skip UUID check.
     */
    const SCENARIO_CREATE_AJAX = 'create-ajax';

    /**
     * @param string $uuid
     * @param int $status
     * @return bool
     * @throws \Exception
     */
    public static function setStatus(string $uuid, int $status)
    {
        $profile = Profile::findOne(['uuid' => $uuid]);
        if ($profile) {
            $profile->status = $status;
            try {
                if ($profile->save()) {
                    return true;
                }
            } catch (\Exception $e) {
                throw new \Exception('Не удалось сохранить Статус для Клиента #' . $uuid, 500);
            }
            throw new \Exception('Не удалось задать Статус для Клиента #' . $uuid . ' ' . Html::errorSummary($profile), 400);
        }
        throw new \Exception('Не удалось найти Клиента #' . $uuid, 404);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'author_id',
                'updatedByAttribute' => 'editor_id',
            ],
        ];
    }

    public function scenarios()
    {
        return array_merge(parent::scenarios(), [
            self::SCENARIO_CREATE => ['l_name', 'f_name', 'm_name', 'phone', 'uuid'],
            self::SCENARIO_CREATE_AJAX => ['l_name', 'f_name', 'm_name', 'phone'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'phone' => 'Телефон',
            'f_name' => 'Фамилия',
            'l_name' => 'Имя',
            'm_name' => 'Отчество',
            'balance' => 'Баланс',
            'status' => 'Статус',
        ];
    }

    public function getFio()
    {
        return implode(' ', [
            $this->l_name,
            $this->f_name,
            $this->m_name
        ]);
    }
}
