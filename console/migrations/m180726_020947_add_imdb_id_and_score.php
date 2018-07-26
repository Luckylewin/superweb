<?php

use yii\db\Migration;

/**
 * Class m180726_020947_add_imdb_id_and_score
 */
class m180726_020947_add_imdb_id_and_score extends Migration
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
        echo "m180726_020947_add_imdb_id_and_score cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('iptv_vod', 'vod_imdb_id', "char(16) not null default ''");
        $this->addColumn('iptv_vod', 'vod_imdb_score', "char(4) not null default '0.0'");
    }

    public function down()
    {
        echo "m180726_020947_add_imdb_id_and_score cannot be reverted.\n";
        $this->dropColumn('iptv_vod', 'vod_imdb_id');
        $this->dropColumn('iptv_vod', 'vod_imdb_score');
        return false;
    }

}
