<?php

use yii\db\Migration;

/**
 * Class m180926_091333_add_savepostion_to_apk_detail_table
 */
class m180926_091333_add_savepostion_to_apk_detail_table extends Migration
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
        echo "m180926_091333_add_savepostion_to_apk_detail_table cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('apk_detail', 'save_position', 'char(5) not null default "oss"');
    }

    public function down()
    {
        echo "m180926_091333_add_savepostion_to_apk_detail_table cannot be reverted.\n";

        return false;
    }

}
