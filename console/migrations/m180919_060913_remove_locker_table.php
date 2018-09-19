<?php

use yii\db\Migration;

/**
 * Class m180919_060913_remove_locker_table
 */
class m180919_060913_remove_locker_table extends Migration
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
        echo "m180919_060913_remove_locker_table cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->dropTable('app_locker_switcher');
        $this->addColumn('mac', 'is_hide', 'char(1) not null default 1');
    }

    public function down()
    {
        echo "m180919_060913_remove_locker_table cannot be reverted.\n";

        return false;
    }

}
