<?php

use yii\db\Migration;

/**
 * Class m180914_070748_add_del_flag_to_order
 */
class m180914_070748_add_del_flag_to_order extends Migration
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
        echo "m180914_070748_add_del_flag_to_order cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('iptv_order', 'del_flag', 'char(2) not null default "0"');
    }

    public function down()
    {
        echo "m180914_070748_add_del_flag_to_order cannot be reverted.\n";

        return true;
    }

}
