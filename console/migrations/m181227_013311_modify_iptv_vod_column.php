<?php

use yii\db\Migration;

/**
 * Class m181227_013311_modify_iptv_vod_column
 */
class m181227_013311_modify_iptv_vod_column extends Migration
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
        echo "m181227_013311_modify_iptv_vod_column cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->alterColumn(\common\models\Vodlink::tableName(),'episode', $this->integer(11));
    }

    public function down()
    {
        echo "m181227_013311_modify_iptv_vod_column cannot be reverted.\n";

        return true;
    }

}
