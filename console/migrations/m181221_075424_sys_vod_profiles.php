<?php

use yii\db\Migration;

/**
 * Class m181221_075424_sys_vod_profiles
 */
class m181221_075424_sys_vod_profiles extends Migration
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
        echo "m181221_075424_sys_vod_profiles cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $tableOptions = "CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=innodb";
        $this->createTable('sys_vod_profiles', [
            'id' => $this->primaryKey(),
            'name' => $this->string(60),
            'alias_name' => $this->string(60),
            'director' => $this->string(20),
            'actor' => $this->string(60),
            'area' => $this->string(20),
            'language' => $this->string(20),
            'type' => $this->string('50'),
            'tab' => $this->string('20'),
            'plot' => $this->string('500'),
            'year' => $this->string(4),
            'date' => $this->string('10'),
            'imdb_id' => $this->integer(),
            'imdb_score' => $this->string(3),
            'tmdb_id' => $this->integer(),
            'tmdb_score' => $this->string(3),
            'douban_id' => $this->integer(),
            'douban_score' => $this->string('3'),
            'length' => $this->string(6),
            'cover' => $this->string(255),
            'banner' => $this->string('255'),
            'comment' => $this->string(2000),
            'fill_status' => $this->tinyInteger(1)
        ], $tableOptions);

        $this->createIndex('name', 'sys_vod_profiles','name');

    }

    public function down()
    {
        echo "m181221_075424_sys_vod_profiles cannot be reverted.\n";
        $this->dropTable('sys_vod_profiles');

        return true;
    }

}
