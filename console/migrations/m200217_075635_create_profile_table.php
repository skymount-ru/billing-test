<?php

use common\models\Profile;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%profile}}`.
 */
class m200217_075635_create_profile_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%profile}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'uuid' => $this->string(36)->notNull(),
            'phone' => $this->string(),
            'f_name' => $this->string()->notNull(),
            'l_name' => $this->string()->notNull(),
            'm_name' => $this->string(),
            'balance' => $this->money(2)->defaultValue(0),
            'status' => $this->tinyInteger()->defaultValue(Profile::STATUS_INACTIVE),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'author_id' => $this->integer(),
            'editor_id' => $this->integer(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%profile}}');
    }
}
