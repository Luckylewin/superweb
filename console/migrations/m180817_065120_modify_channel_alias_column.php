<?php

use yii\db\Migration;

/**
 * Class m180817_065120_modify_channel_alias_column
 */
class m180817_065120_modify_channel_alias_column extends Migration
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
        echo "m180817_065120_modify_channel_alias_column cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->alterColumn('ott_channel', 'alias_name', 'VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL');
    }

    public function down()
    {
        echo "m180817_065120_modify_channel_alias_column cannot be reverted.\n";

        return false;
    }

}
