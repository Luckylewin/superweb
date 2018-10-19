<?php

use yii\db\Migration;

/**
 * Class m181019_065858_add_vod_link_from
 */
class m181019_065858_add_vod_link_from extends Migration
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
        echo "m181019_065858_add_vod_link_from cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('iptv_vodlink', 'save_type', "char(10) not null default 'external'");
    }

    public function down()
    {
        echo "m181019_065858_add_vod_link_from cannot be reverted.\n";
        $this->dropColumn('iptv_vodlink', 'save_type');
        return true;
    }

}
