<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "payment".
 *
 * @property int $id
 * @property string $uuid
 * @property int $profile_id
 * @property float $balance
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $author_id
 * @property int|null $editor_id
 */
class BasePayment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uuid', 'profile_id', 'balance'], 'required'],
            [['profile_id', 'created_at', 'updated_at', 'author_id', 'editor_id'], 'integer'],
            [['balance'], 'number'],
            [['uuid'], 'string', 'max' => 36],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uuid' => 'Uuid',
            'profile_id' => 'Profile ID',
            'balance' => 'Balance',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'author_id' => 'Author ID',
            'editor_id' => 'Editor ID',
        ];
    }
}
