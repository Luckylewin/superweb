<?php

use yii\db\Migration;

/**
 * Class m181203_022220_add_use_time_recored_table
 */
class m181203_022220_add_use_time_recored_table extends Migration
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
        echo "m181203_022220_add_use_time_recored_table cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('yii2_menu', 'type', 'char(10) not null default "all"');
    }

    public function down()
    {
        $this->dropColumn('yii2_menu', 'type');

        return false;
    }

}
