<?php

use yii\db\Migration;

/**
 * Class m180820_073029_add_client_id_column_to_mac
 */
class m180820_073029_add_client_id_column_to_mac extends Migration
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
        echo "m180820_073029_add_client_id_column_to_mac cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('mac', 'client_id', 'smallint(6) not null default 0');
    }

    public function down()
    {
        echo "m180820_073029_add_client_id_column_to_mac cannot be reverted.\n";

        return false;
    }

}
