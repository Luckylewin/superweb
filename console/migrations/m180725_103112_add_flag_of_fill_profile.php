<?php

use yii\db\Migration;

/**
 * Class m180725_103112_add_flag_of_fill_profile
 */
class m180725_103112_add_flag_of_fill_profile extends Migration
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
        echo "m180725_103112_add_flag_of_fill_profile cannot be reverted.\n";

        return false;
    }

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('iptv_vod', 'vod_fill_flag', 'char(1) not null default 0');
        return true;
    }

    public function down()
    {
        echo "m180725_102237_flag_of_fill_profile cannot be reverted.\n";
        $this->dropColumn('iptv_vod', 'vod_fill_flag');
        return true;
    }
}
