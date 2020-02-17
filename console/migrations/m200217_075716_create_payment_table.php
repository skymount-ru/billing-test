<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%payment}}`.
 */
class m200217_075716_create_payment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%payment}}', [
            'id' => $this->primaryKey(),
            'uuid' => $this->string(36)->notNull(),
            'profile_id' => $this->integer()->notNull(),
            'amount' => $this->money(2)->notNull(),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'author_id' => $this->integer(),
            'editor_id' => $this->integer(),
        ]);

        // $this->addForeignKey('fk-payment-profile', '{{%payment}}', 'profile_id', '{{%profile}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%payment}}');
    }
}
