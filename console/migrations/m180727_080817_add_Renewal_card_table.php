<?php

use yii\db\Migration;

/**
 * Class m180727_080817_add_Renewal_card_table
 */
class m180727_080817_add_Renewal_card_table extends Migration
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
        echo "m180727_080817_add_Renewal_card_table cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('sys_renewal_card', [
            'card_num' => $this->string(16)->notNull()->comment('卡号'),
            'card_secret' => $this->string(16)->notNull()->comment('卡密'),
            'card_contracttime' => $this->string(10)->defaultValue('1 month')->comment('续费时长'),
            'is_del' => $this->string(1)->notNull()->defaultValue('0')->comment('是否被删除'),
            'is_valid' => $this->string('1')->notNull()->defaultValue('0')->comment('是否已使用'),
            'created_time' => $this->integer(10)->notNull()->defaultValue('0')->comment('创建时间'),
            'updated_time' => $this->integer(10)->notNull()->defaultValue('0')->comment('更新时间'),
            'batch' => $this->integer(6)->notNull()->defaultValue('0')->comment('批次'),
            'who_use' => $this->string(30)->notNull()->defaultValue('')->comment('使用的人')
        ], $tableOptions);

        $this->addPrimaryKey('key_card_num', 'sys_renewal_card', 'card_num');
    }

    public function down()
    {
        echo "m180727_080817_add_Renewal_card_table cannot be reverted.\n";
        $this->dropTable('sys_renewal_card');
        return true;
    }

}
