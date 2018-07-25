<?php

use yii\db\Migration;

/**
 * Class m180725_105118_modify_vod_area_column
 */
class m180725_105118_modify_vod_area_column extends Migration
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
        echo "m180725_105118_modify_vod_area_column cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->alterColumn('iptv_vod', 'vod_area', 'char(20)');
    }

    public function down()
    {
        echo "m180725_105118_modify_vod_area_column cannot be reverted.\n";

        return false;
    }

}
