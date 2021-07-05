<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%currency}}`.
 */
class m210518_123358_CreateCurrencyTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('currency', [
            'id' => $this->primaryKey(),
            'code' => $this->integer(10)->notNull(),
            'symbol_code' => $this->string(255)->notNull(),
            'units' => $this->integer(255)->notNull(),
            'currency' => $this->string(255)->notNull(),
            'course' => $this->float()->notNull(),
            'coefficient' => $this->string()->notNull(),
            'date' => $this->dateTime()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('currency');
    }
}
