<?php

use yii\db\Migration;

/**
 * Class m181115_093029_add_during_to_link
 */
class m181115_093029_add_during_to_link extends Migration
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
        echo "m181115_093029_add_during_to_link cannot be reverted.\n";

        return false;
    }

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('iptv_vodlink', 'during', "varchar(20) not null default ''");
    }

    public function down()
    {
        echo "m181115_093029_add_during_to_link cannot be reverted.\n";
        $this->dropColumn('iptv_vodlink', 'during');

        return true;
    }
}
