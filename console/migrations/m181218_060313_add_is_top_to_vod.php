<?php

use yii\db\Migration;

/**
 * Class m181218_060313_add_is_top_to_vod
 */
class m181218_060313_add_is_top_to_vod extends Migration
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
        echo "m181218_060313_add_is_top_to_vod cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn(\common\models\Vod::tableName(), 'is_top', 'tinyint(1) not null default 0');
    }

    public function down()
    {
        echo "m181218_060313_add_is_top_to_vod cannot be reverted.\n";
        $this->dropColumn(\common\models\Vod::tableName(), 'is_top');
        return true;
    }

}
