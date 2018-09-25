<?php

use yii\db\Migration;

/**
 * Class m180921_055436_add_serial_number_to_iptv_order
 */
class m180921_055436_add_serial_number_to_iptv_order extends Migration
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
        echo "m180921_055436_add_serial_number_to_iptv_order cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        // 添加平台订单号
        $this->addColumn('iptv_order', 'transNo', 'varchar(64) not null default ""');
    }

    public function down()
    {
        echo "m180921_055436_add_serial_number_to_iptv_order cannot be reverted.\n";
        $this->dropColumn('iptv_order', 'TransNo');
        return true;
    }

}
