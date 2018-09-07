<?php

use yii\db\Migration;

/**
 * Class m180905_025432_add_probation_table
 */
class m180905_025432_add_probation_table extends Migration
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
        echo "m180905_025432_add_probation_table cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.

    // 分类收费试用表
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $this->createTable('ott_genre_probation', [
            'mac' => $this->string(50)->comment('mac地址'),
            'genre' => $this->string(20)->comment('列表名称'),
            'day' => $this->integer(6)->comment('免费体验天数'),
            'expire_time' => $this->integer(10)->comment('过期时间'),
            'created_at' => $this->integer(10)->comment('创建时间'),
            'updated_at' => $this->integer(10)->comment('更新时间'),
        ], $tableOptions);

        $this->createIndex('mac_index', 'ott_genre_probation', 'mac');
    }

    public function down()
    {
        echo "m180905_025432_add_probation_table cannot be reverted.\n";
        $this->dropTable('ott_genre_probation');
        return false;
    }

}
