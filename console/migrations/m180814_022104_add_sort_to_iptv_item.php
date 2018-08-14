<?php

use yii\db\Migration;

/**
 * Class m180814_022104_add_sort_to_iptv_item
 */
class m180814_022104_add_sort_to_iptv_item extends Migration
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
        echo "m180814_022104_add_sort_to_iptv_item cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('iptv_type', 'sort', 'int(10) not null default 0');
    }

    public function down()
    {
        echo "m180814_022104_add_sort_to_iptv_item cannot be reverted.\n";

        return false;
    }

}
