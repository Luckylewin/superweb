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
            'name' => $this->string(255),
            'alias_name' => $this->string(255),
            'screen_writer' => $this->string(255),
            'director' => $this->string(255),
            'actor' => $this->string(255),
            'area' => $this->string(255),
            'language' => $this->string(255),
            'type' => $this->string('255'),
            'tag' => $this->string('255'),
            'user_tag' => $this->string(255),
            'plot' => $this->text(),
            'year' => $this->string(4),
            'date' => $this->string('10'),
            'imdb_id' => $this->char(14),
            'imdb_score' => $this->string(3),
            'tmdb_id' => $this->integer(),
            'tmdb_score' => $this->string(3),
            'douban_id' => $this->integer(),
            'douban_score' => $this->string('3'),
            'douban_voters' => $this->integer('3'),
            'length' => $this->string(6),
            'cover' => $this->string(255),
            'image' => $this->string(255),
            'banner' => $this->string('255'),
            'comment' => $this->text(),
            'fill_status' => $this->tinyInteger(1)->defaultValue(0),
            'douban_search' => $this->tinyInteger(1)->defaultValue(0),
            'imdb_search' => $this->tinyInteger(1)->defaultValue(0),
            'tmdb_search' => $this->tinyInteger(1)->defaultValue(0),
            'profile_language' => $this->char(10)->defaultValue('zh-CN'),
            'media_type' => $this->char(20)->defaultValue('movie')
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
