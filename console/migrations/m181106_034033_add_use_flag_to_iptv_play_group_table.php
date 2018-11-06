<?php

use yii\db\Migration;

/**
 * Class m181106_034033_add_use_flag_to_iptv_play_group_table
 */
class m181106_034033_add_use_flag_to_iptv_play_group_table extends Migration
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
        echo "m181106_034033_add_use_flag_to_iptv_play_group_table cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn(\backend\models\PlayGroup::tableName(), 'use_flag', 'tinyint(1) not null default 1');
    }

    public function down()
    {
        echo "m181106_034033_add_use_flag_to_iptv_play_group_table cannot be reverted.\n";
        $this->dropColumn(\backend\models\PlayGroup::tableName(), 'use_flag');
        return true;
    }

}
