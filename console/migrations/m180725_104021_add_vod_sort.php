<?php

use yii\db\Migration;

/**
 * Class m180725_104021_add_vod_sort
 */
class m180725_104021_add_vod_sort extends Migration
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
        echo "m180725_104021_add_vod_sort cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('iptv_vod', 'sort', 'int(8) not null default 0');
        return true;
    }

    public function down()
    {
        echo "m180725_104021_add_vod_sort cannot be reverted.\n";
        $this->dropColumn('iptv_vod', 'sort');
        return false;
    }

}
