<?php

use yii\db\Migration;

/**
 * Class m181126_064039_modify_iptv_type_add_suport_language
 */
class m181126_064039_modify_iptv_type_add_suport_language extends Migration
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
        echo "m181126_064039_modify_iptv_type_add_suport_language cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('iptv_list','supported_language', 'varchar(255) not null default ""');
    }

    public function down()
    {
        echo "m181126_064039_modify_iptv_type_add_suport_language cannot be reverted.\n";
        $this->dropColumn('iptv_list', 'supported_language');
        return true;
    }

}
