<?php

use yii\db\Migration;

/**
 * Class m181220_014150_remove_scheme_id
 */
class m181220_014150_remove_scheme_id extends Migration
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
        echo "m181220_014150_remove_scheme_id cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->dropColumn('apk_list', 'scheme_id');
    }

    public function down()
    {
        echo "m181220_014150_remove_scheme_id cannot be reverted.\n";

        return true;
    }

}
