<?php

use yii\db\Migration;

/**
 * Class m180820_081744_ott_probation_table
 */
class m180820_081744_ott_probation_table extends Migration
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
        echo "m180820_081744_ott_probation_table cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $this->createTable('ott_probation', [
            'mac' => $this->string(50)->comment('mac地址'),
            'day' => $this->integer(6)->comment('免费体验天数'),
            'expire_time' => $this->integer(10)->comment('过期时间')
        ], $tableOptions);

        $this->createIndex('mac_index', 'ott_probation', 'mac');
    }

    public function down()
    {
        echo "m180820_081744_ott_probation_table cannot be reverted.\n";

        return false;
    }

}
