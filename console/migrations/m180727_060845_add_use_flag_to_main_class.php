<?php

use yii\db\Migration;

/**
 * Class m180727_060845_add_use_flag_to_main_class
 */
class m180727_060845_add_use_flag_to_main_class extends Migration
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
        echo "m180727_060845_add_use_flag_to_main_class cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('ott_main_class', 'use_flag',  "char(1) not null default '1'");
    }

    public function down()
    {
        echo "m180727_060845_add_use_flag_to_main_class cannot be reverted.\n";
        $this->dropColumn('ott_main_class', 'use_flag');
        return false;
    }

}
