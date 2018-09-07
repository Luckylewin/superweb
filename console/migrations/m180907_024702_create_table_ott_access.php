<?php

use yii\db\Migration;

/**
 * Class m180907_024702_create_table_ott_access
 */
class m180907_024702_create_table_ott_access extends Migration
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
        echo "m180907_024702_create_table_ott_access cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $this->createTable('ott_access', [
            'mac' => $this->string(50)->comment('mac地址'),
            'genre' => $this->string(20)->comment('列表名称'),
            'is_valid' => $this->integer(6)->comment('是否有权限'),
            'deny_msg' => $this->string('50')->comment('拒绝原因'),
            'expire_time' => $this->integer('10')->comment('过期时间')
        ], $tableOptions);

        $this->createIndex('mac_genre', 'ott_genre_probation', ['mac', 'genre']);
    }

    public function down()
    {
        echo "m180907_024702_create_table_ott_access cannot be reverted.\n";
        $this->dropTable('ott_access');
        return true;
    }

}
