<?php

use yii\db\Migration;

/**
 * Class m180820_030821_alter_image_coloum
 */
class m180820_030821_alter_image_coloum extends Migration
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
        echo "m180820_030821_alter_image_coloum cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->alterColumn('ott_channel', 'image', 'varchar(255) not null default ""');
        $this->alterColumn('ott_channel', 'use_flag', "char(1) not null default '1'");
    }

    public function down()
    {
        echo "m180820_030821_alter_image_coloum cannot be reverted.\n";

        return false;
    }

}
