<?php


namespace common\models;

use common\facade\UUID;
use common\facade\UUIDSchema;
use common\models\db\BaseProfile;
use Yii;
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

    /**
     * Gets query for [[Payments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPayments()
    {
        return $this->hasMany(Payment::class, ['profile_id' => 'id']);
    }

    /**
     * Gets profile by UUID.
     *
     * @param string $uuid
     * @return Profile|null
     */
    public static function findByUuid(string $uuid)
    {
        return Profile::findOne(['uuid' => $uuid]);
    }

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
                throw new \Exception('Не удалось сохранить Статус для Клиента', 500);
            }
            throw new \Exception('Не удалось задать Статус для Клиента. ' . Html::errorSummary($profile), 400);
        }
        throw new \Exception('Не удалось найти Клиента', 404);
    }

    public static function searchUuidList(string $q)
    {
        $query = Profile::find()
            ->where(['status' => Profile::STATUS_ACTIVE])
            ->andWhere(['or',
                ['like', 'uuid', $q],
                ['like', 'phone', $q]
            ]);

        return $query
            ->select(['phone', 'l_name', 'id'])
            ->asArray()
            ->all();
    }

    /**
     * Deposit an amount on profile's account.
     *
     * @param string $id
     * @param int $amount
     * @return bool
     * @throws \Exception
     */
    public static function deposit(string $id, int $amount)
    {
        $profile = Profile::findOne([
            'id' => $id,
            'status' => Profile::STATUS_ACTIVE,
        ]);
        if (!$profile) {
            throw new \Exception('Не удалось найти Клиента', 404);
        }
        $transaction = Yii::$app->db->beginTransaction();
        $payment = new Payment([
            'profile_id' => $profile->id,
            'amount' => $amount,
        ]);
        try {
            if (UUID::fillModelWithValidUUID($payment, UUIDSchema::NS_PAYMENT) && $payment->save()) {
                $profile->refreshBalance();
                $transaction->commit();
                return true;
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw new \Exception('Не удалось сохранить зачисление для Клиента', 500);
        }
        $transaction->rollBack();
        throw new \Exception('Не удалось пополнить баланс Клиента. ' . Html::errorSummary($profile), 400);
    }

    /**
     * Updates balance of the given Profile.
     *
     * @param Profile $profile
     * @throws \Exception
     */
    public function refreshBalance()
    {
        $this->balance = $this->getPayments()->sum('amount');
        if (!$this->save()) {
            throw new \Exception('Unable to update Profile #' . $this->uuid);
        }
    }
}
