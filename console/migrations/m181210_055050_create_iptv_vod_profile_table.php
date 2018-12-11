<?php

use yii\db\Migration;

/**
 * Handles the creation of table `iptv_vod_profile`.
 */
class m181210_055050_create_iptv_vod_profile_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $this->createTable('iptv_vod_profile', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100),
            'profile' => $this->text()
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('iptv_vod_profile');
    }
}
