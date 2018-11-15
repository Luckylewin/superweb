<?php

use yii\db\Migration;

/**
 * Class m181115_100517_add_big_pic_to_iptv_type
 */
class m181115_100517_add_big_pic_to_iptv_type extends Migration
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
        echo "m181115_100517_add_big_pic_to_iptv_type cannot be reverted.\n";

        return false;
    }

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('iptv_type', 'image_hover', 'varchar(255)');
    }

    public function down()
    {
        echo "m181115_100517_add_big_pic_to_iptv_type cannot be reverted.\n";
        $this->dropColumn('iptv_type', 'image_hover');
        return true;
    }

}
