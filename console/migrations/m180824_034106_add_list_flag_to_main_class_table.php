<?php

use yii\db\Migration;

/**
 * Class m180824_034106_add_list_flag_to_main_class_table
 */
class m180824_034106_add_list_flag_to_main_class_table extends Migration
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
        echo "m180824_034106_add_list_flag_to_main_class_table cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        // 添加一个字段用于展示名称
        $this->addColumn('ott_main_class', 'list_name', 'varchar(50) not null');
        try {
            Yii::$app->db->createCommand('update ott_main_class set list_name=name')->execute()
            } catch (\Exception $e) {

        }
    }

    public function down()
    {
        echo "m180824_034106_add_list_flag_to_main_class_table cannot be reverted.\n";

        return false;
    }

}
