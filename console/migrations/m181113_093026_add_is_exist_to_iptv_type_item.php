<?php

use yii\db\Migration;

/**
 * Class m181113_093026_add_is_exist_to_iptv_type_item
 */
class m181113_093026_add_is_exist_to_iptv_type_item extends Migration
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
        echo "m181113_093026_add_is_exist_to_iptv_type_item cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('iptv_type_item', 'exist_num', 'smallint not null default 0');
    }

    public function down()
    {
        echo "m181113_093026_add_is_exist_to_iptv_type_item cannot be reverted.\n";
        $this->dropColumn('iptv_type_item', 'exist_num');

        return true;
    }

}
