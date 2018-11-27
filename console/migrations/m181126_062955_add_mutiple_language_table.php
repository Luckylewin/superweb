<?php

use yii\db\Migration;

/**
 * Class m181126_062955_add_mutiple_language_table
 */
class m181126_062955_add_mutiple_language_table extends Migration
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
        echo "m181126_062955_add_mutiple_language_table cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $this->createTable('sys_multi_lang', [
            'id'          => $this->primaryKey(),
            'fid'         => $this->integer(),
            'table'       => $this->string(20)->comment('表名')->defaultValue(\backend\models\IptvTypeItem::tableName()),
            'field'       => $this->string(20)->comment('字段名'),
            'origin'      => $this->string(800)->comment('原值')->defaultValue(''),
            'value'       => $this->string(800)->comment('值'),
            'language'    => $this->string('10')->comment('语言')
        ], $tableOptions);

        $this->createIndex('fid', 'sys_multi_lang', ['fid']);
    }

    public function down()
    {
        echo "m181126_062955_add_mutiple_language_table cannot be reverted.\n";
        $this->dropTable('sys_multi_lang');
        return true;
    }

}
