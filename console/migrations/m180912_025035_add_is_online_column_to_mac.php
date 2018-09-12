<?php

use yii\db\Migration;

/**
 * Class m180912_025035_add_is_online_column_to_mac
 */
class m180912_025035_add_is_online_column_to_mac extends Migration
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
        echo "m180912_025035_add_is_online_column_to_mac cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('mac', 'is_online', 'char(1) not null default "0"');
    }

    public function down()
    {
        echo "m180912_025035_add_is_online_column_to_mac cannot be reverted.\n";
        $this->dropColumn('mac', 'is_online');
        return false;
    }

}
