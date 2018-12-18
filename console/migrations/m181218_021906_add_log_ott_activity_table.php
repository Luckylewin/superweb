<?php

use yii\db\Migration;

/**
 * Class m181218_021906_add_log_ott_activity_table
 */
class m181218_021906_add_log_ott_activity_table extends Migration
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
        echo "m181218_021906_add_log_ott_activity_table cannot be reverted.\n";

        return false;
    }

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $tableOptions = "CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=innodb";

        $this->addColumn(\common\models\MainClass::tableName(),'is_log',"tinyint(1) default 0");

        $this->createTable('log_ott_activity', [
            'id' => $this->primaryKey(),
            'date' => $this->date(),
            'timestamp' => $this->integer(10),
            'mac' =>  $this->string('32'),
            'genre' => $this->string(30),
            'code' => $this->string('32')
        ], $tableOptions);
    }

    public function down()
    {
        echo "m181218_021906_add_log_ott_activity_table cannot be reverted.\n";
        $this->dropTable('log_ott_activity');

        return true;
    }

}
