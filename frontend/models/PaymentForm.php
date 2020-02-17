<?php

namespace frontend\models;

use common\models\Profile;
use yii\base\Model;

/**
 * PaymentForm is the model behind the contact form.
 */
class PaymentForm extends Model
{
    public $id;
    public $amount;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'amount'], 'required'],
            ['id', 'integer'],
            ['id', 'validateId'],
            ['amount', 'integer', 'min' => 1, 'max' => 999999],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'Идентификатор клиента',
            'amount' => 'Сумма для зачисления',
        ];
    }

    public function validateId($attribute, $params, $validator)
    {
        if (!Profile::find()->where(['id' => $this->$attribute])->exists()) {
            $this->addError('id', 'Выбран некоректный ID клиента (не существует)');
        }
    }
}
