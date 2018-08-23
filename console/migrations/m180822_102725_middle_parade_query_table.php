<?php

use yii\db\Migration;

/**
 * Class m180822_102725_middle_parade_query_table
 */
class m180822_102725_middle_parade_query_table extends Migration
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
        echo "m180822_102725_middle_parade_query_table cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $this->createTable('iptv_middle_parade', [
            'genre' => $this->string(30)->comment('分类'),
            'channel' => $this->string(50)->comment('频道'),
            'parade_content' => $this->text()->comment('预告内容')
        ], $tableOptions);
    }

    public function down()
    {
        echo "m180822_102725_middle_parade_query_table cannot be reverted.\n";

        return false;
    }

}
