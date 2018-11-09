<?php

use yii\db\Migration;

/**
 * Class m181108_072624_add_link_title_to_vod_link_table
 */
class m181108_072624_add_link_title_to_vod_link_table extends Migration
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
        echo "m181108_072624_add_link_title_to_vod_link_table cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('iptv_vodlink', 'title', 'varchar(100) default null');
    }

    public function down()
    {
        echo "m181108_072624_add_link_title_to_vod_link_table cannot be reverted.\n";
        $this->dropColumn('iptv_vodlink', 'title');
        return true;
    }

}
