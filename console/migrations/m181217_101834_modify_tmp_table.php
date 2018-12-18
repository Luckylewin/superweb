<?php

use yii\db\Migration;

/**
 * Class m181217_101834_modify_tmp_table
 */
class m181217_101834_modify_tmp_table extends Migration
{


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $tableOptions = "CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=innodb";

        $this->createTable('log_ott_genre_tmp', [
            'id' => $this->primaryKey(),
            'mac' => $this->string(32),
            'genre' => $this->string(30),
            'code' => $this->integer()
        ], $tableOptions);

        $this->dropColumn('log_tmp_interface','data');
    }

    public function down()
    {
        echo "m181217_101834_modify_tmp_table cannot be reverted.\n";
        $this->dropTable('log_ott_genre_tmp');

        return true;
    }

}
