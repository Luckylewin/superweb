<?php

use yii\db\Migration;

/**
 * Class m180730_083115_add_scheme_admin_table
 */
class m180730_083115_add_scheme_admin_table extends Migration
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
        echo "m180730_083115_add_scheme_admin_table cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $this->createTable('sys__admin_scheme', [
            'scheme_id' => $this->integer(6),
            'admin_id' => $this->integer(6)
        ], $tableOptions);
    }

    public function down()
    {
        echo "m180730_083115_add_scheme_admin_table cannot be reverted.\n";
        $this->dropTable('sys__admin_scheme');
        return true;
    }

}
