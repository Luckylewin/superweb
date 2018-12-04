<?php

use yii\db\Migration;

use common\models\OttChannel;

/**
 * Class m181204_080653_add_reboardcase_to_ott_channel
 */
class m181204_080653_add_reboardcase_to_ott_channel extends Migration
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
        echo "m181204_080653_add_reboardcase_to_ott_channel cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        // 回播可用，回播算法，时移可用，时移算法
        $this->addColumn(OttChannel::tableName(), 'rebroadcast_use_flag', 'tinyint(1) not null default 0');
        $this->addColumn(OttChannel::tableName(), 'rebroadcast_method', 'char(20) not null default ""');
        $this->addColumn(OttChannel::tableName(), 'shifting_use_flag', 'tinyint(1) not null default 0');
        $this->addColumn(OttChannel::tableName(), 'shifting_method', 'char(20) not null default ""');
    }

    public function down()
    {
        echo "m181204_080653_add_reboardcase_to_ott_channel cannot be reverted.\n";

        $this->dropColumn(OttChannel::tableName(), 'rebroadcast_use_flag');
        $this->dropColumn(OttChannel::tableName(), 'rebroadcast_method');
        $this->dropColumn(OttChannel::tableName(), 'shifting_use_flag');
        $this->dropColumn(OttChannel::tableName(), 'shifting_method');

        return true;
    }

}
