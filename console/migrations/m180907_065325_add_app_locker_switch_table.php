<?php

use yii\db\Migration;

/**
 * Class m180907_065325_add_app_locker_switch_table
 */
class m180907_065325_add_app_locker_switch_table extends Migration
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
        echo "m180907_065325_add_app_locker_switch_table cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->alterColumn('ott_genre_probation', 'mac', 'varchar(32) not null');
        $this->alterColumn('ott_genre_probation', 'day', 'date not null');
        $tableOptions = "CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB";
        $this->createTable('app_locker_switcher', [
            'mac' => $this->string(32)->comment('mac地址'),
            'app_name' => $this->string(20)->comment('app名称'),
            'switch' => $this->char(3)->defaultValue('off')->comment('开关:on|off')
        ], $tableOptions);

    }

    public function down()
    {
        echo "m180907_065325_add_app_locker_switch_table cannot be reverted.\n";
        $this->dropTable('app_locker_switcher');
        return true;
    }

}
