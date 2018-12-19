<?php

use yii\db\Migration;

/**
 * Class m181219_030209_modify_activity_table
 */
class m181219_030209_modify_activity_table extends Migration
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
        echo "m181219_030209_modify_activity_table cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('log_ott_activity', 'scheme', 'char(20) not null default ""');
    }

    public function down()
    {
        echo "m181219_030209_modify_activity_table cannot be reverted.\n";

        $this->dropColumn('log_ott_activity', "scheme");

        return true;
    }

}
