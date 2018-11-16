<?php

use yii\db\Migration;

/**
 * Class m181116_055307_add_columb_is_show_to_iptv_type_table
 */
class m181116_055307_add_columb_is_show_to_iptv_type_table extends Migration
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
        echo "m181116_055307_add_columb_is_show_to_iptv_type_table cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('iptv_type', 'is_show', 'tinyint(1) not null default 1');
    }

    public function down()
    {
        echo "m181116_055307_add_columb_is_show_to_iptv_type_table cannot be reverted.\n";
        $this->dropColumn('iptv_type', 'is_show');

        return true;
    }

}
