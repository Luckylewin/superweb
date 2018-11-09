<?php

use yii\db\Migration;

/**
 * Class m181109_053641_add_origin_url_to_table_iptv_vod
 */
class m181109_053641_add_origin_url_to_table_iptv_vod extends Migration
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
        echo "m181109_053641_add_origin_url_to_table_iptv_vod cannot be reverted.\n";

        return false;
    }

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('iptv_vod', 'vod_origin_url', 'varchar(255) default null');
    }

    public function down()
    {
        echo "m181109_053641_add_origin_url_to_table_iptv_vod cannot be reverted.\n";
        $this->dropColumn('iptv_vod', 'vod_origin_url');
        return true;
    }

}
