<?php

use yii\db\Migration;

/**
 * Class m181105_012531_add_pic_column_to_vodlink
 */
class m181105_012531_add_pic_column_to_vodlink extends Migration
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
        echo "m181105_012531_add_pic_column_to_vodlink cannot be reverted.\n";

        return false;
    }

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('iptv_vodlink','pic', 'varchar(255) default null');
        $this->alterColumn('iptv_vod', 'vod_length', 'varchar(10) default null');
    }

    public function down()
    {
        echo "m181105_012531_add_pic_column_to_vodlink cannot be reverted.\n";
        $this->dropColumn('iptv_vodlink', 'pic');

        return true;
    }
}
