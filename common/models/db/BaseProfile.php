<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "profile".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $uuid
 * @property string|null $phone
 * @property string $f_name
 * @property string $l_name
 * @property string|null $m_name
 * @property float|null $balance
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $author_id
 * @property int|null $editor_id
 */
class BaseProfile extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'profile';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'status', 'created_at', 'updated_at', 'author_id', 'editor_id'], 'integer'],
            [['uuid', 'f_name', 'l_name'], 'required'],
            [['balance'], 'number'],
            [['uuid'], 'string', 'max' => 36],
            [['phone', 'f_name', 'l_name', 'm_name'], 'string', 'max' => 255],
            [['phone'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'uuid' => 'Uuid',
            'phone' => 'Phone',
            'f_name' => 'F Name',
            'l_name' => 'L Name',
            'm_name' => 'M Name',
            'balance' => 'Balance',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'author_id' => 'Author ID',
            'editor_id' => 'Editor ID',
        ];
    }
}
