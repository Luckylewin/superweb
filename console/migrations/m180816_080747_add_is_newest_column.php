<?php

use yii\db\Migration;

/**
 * Class m180816_080747_add_is_newest_column
 */
class m180816_080747_add_is_newest_column extends Migration
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
        echo "m180816_080747_add_is_newest_column cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('apk_detail', 'is_newest', 'char(1) not null default 0');
    }

    public function down()
    {
        echo "m180816_080747_add_is_newest_column cannot be reverted.\n";

        return false;
    }

}
