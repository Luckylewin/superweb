<?php

use yii\db\Migration;

/**
 * Class m181212_072148_modify_profile_table
 */
class m181212_072148_modify_profile_table extends Migration
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
        echo "m181212_072148_modify_profile_table cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn(\backend\models\VodProfile::tableName(), 'language', "char(5) not null default 'en-US'");
    }

    public function down()
    {
        echo "m181212_072148_modify_profile_table cannot be reverted.\n";
        $this->dropColumn(\backend\models\VodProfile::tableName(), 'language');

        return true;
    }

}
