<?php

use yii\db\Migration;

/**
 * Class m181122_085448_modify_menu_column
 */
class m181122_085448_modify_menu_column extends Migration
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
        echo "m181122_085448_modify_menu_column cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->alterColumn('yii2_menu', 'url', 'varchar(255) not null default "/"');
    }

    public function down()
    {
        echo "m181122_085448_modify_menu_column cannot be reverted.\n";

        return true;
    }

}
