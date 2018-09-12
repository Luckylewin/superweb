<?php

use yii\db\Migration;

/**
 * Class m180912_090033_add_free_trail_day_to_main_class
 */
class m180912_090033_add_free_trail_day_to_main_class extends Migration
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
        echo "m180912_090033_add_free_trail_day_to_main_class cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('ott_main_class', 'free_trail_days', 'smallint(6) not null default 1');
    }

    public function down()
    {
        echo "m180912_090033_add_free_trail_day_to_main_class cannot be reverted.\n";

        return false;
    }

}
