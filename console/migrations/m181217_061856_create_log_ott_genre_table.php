<?php

use yii\db\Migration;

/**
 * Handles the creation of table `log_ott_genre`.
 */
class m181217_061856_create_log_ott_genre_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = "CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=innodb";

        $this->createTable('log_ott_genre', [

            'id' => $this->primaryKey(),
            'date' => $this->date(),
            'genre' => $this->string(30),
            'download_time' => $this->integer(),
            'person_time' => $this->integer(),
            'same_version_time' => $this->integer()

        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('log_ott_genre');
    }
}
