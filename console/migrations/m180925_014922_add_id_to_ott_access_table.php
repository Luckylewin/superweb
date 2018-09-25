<?php

use yii\db\Migration;

/**
 * Class m180925_014922_add_id_to_ott_access_table
 */
class m180925_014922_add_id_to_ott_access_table extends Migration
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
        echo "m180925_014922_add_id_to_ott_access_table cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('ott_access', 'id', 'int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY');
    }

    public function down()
    {
        echo "m180925_014922_add_id_to_ott_access_table cannot be reverted.\n";

        return true;
    }

}
