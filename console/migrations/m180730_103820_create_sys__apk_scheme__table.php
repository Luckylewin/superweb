<?php

use yii\db\Migration;

/**
 * Handles the creation of table `sys__apk_scheme_`.
 */
class m180730_103820_create_sys__apk_scheme__table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('sys__apk_scheme_');
    }

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $this->createTable('sys__apk_scheme', [
            'apk_id' => $this->integer(6),
            'scheme_id' => $this->integer(6)
        ], $tableOptions);
    }

    public function down()
    {
        echo "m180730_103820_create_sys__apk_scheme__table cannot be reverted.\n";
        $this->dropTable('sys__apk_scheme');
        return true;
    }
}
