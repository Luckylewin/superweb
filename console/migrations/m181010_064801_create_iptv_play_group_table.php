<?php

use yii\db\Migration;

/**
 * Handles the creation of table `iptv_play_group`.
 */
class m181010_064801_create_iptv_play_group_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $this->createTable('iptv_play_group', [
            'id' => $this->primaryKey(),
            'vod_id' => $this->integer(11),
            'group_name' => $this->string(32),
            'sort' => $this->integer()->defaultValue(0),
        ], $tableOptions);

        $this->addColumn('iptv_vodlink', 'group_id', 'int(11)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('iptv_play_group');
        $this->dropColumn('iptv_vodlink', 'group_id');
    }
}
