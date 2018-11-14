<?php

use yii\db\Migration;

/**
 * Class m181114_095157_add_image_to_iptv_item
 */
class m181114_095157_add_image_to_iptv_item extends Migration
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
        echo "m181114_095157_add_image_to_iptv_item cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('iptv_type', 'image', 'varchar(255) not null default ""');
    }

    public function down()
    {
        echo "m181114_095157_add_image_to_iptv_item cannot be reverted.\n";
        $this->dropColumn('iptv_type', 'image');
        return true;
    }

}
