<?php

use yii\db\Migration;

/**
 * Class m180917_031529_add_column_access_key_to_ott_access
 */
class m180917_031529_add_column_access_key_to_ott_access extends Migration
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
        echo "m180917_031529_add_column_access_key_to_ott_access cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        //$this->addColumn('ott_access', 'access_key', 'char(32) not null');
        $this->addColumn('ott_order', 'access_key', 'char(32) not null');
    }

    public function down()
    {
        echo "m180917_031529_add_column_access_key_to_ott_access cannot be reverted.\n";

        return true;
    }

}
