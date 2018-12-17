<?php

use yii\db\Migration;

/**
 * Class m181217_072641_alter_log_temp_interface_table
 */
class m181217_072641_alter_log_temp_interface_table extends Migration
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
        echo "m181217_072641_alter_log_temp_interface_table cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('log_tmp_interface','data', 'tinytext');
        $this->addColumn('log_tmp_interface','code', 'char(5)');
    }

    public function down()
    {
        echo "m181217_072641_alter_log_temp_interface_table cannot be reverted.\n";

        $this->dropColumn('log_tmp_interface','data');
        $this->dropColumn('log_tmp_interface','code');

        return true;
    }

}
