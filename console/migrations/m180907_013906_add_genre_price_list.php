<?php

use yii\db\Migration;

/**
 * Class m180907_013906_add_genre_price_list
 */
class m180907_013906_add_genre_price_list extends Migration
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
        echo "m180907_013906_add_genre_price_list cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('ott_main_class','one_month_price', 'decimal(8,2) default 0.00');
        $this->addColumn('ott_main_class','three_month_price', 'decimal(8,2) default 0.00');
        $this->addColumn('ott_main_class','six_month_price', 'decimal(8,2) default 0.00');
        $this->addColumn('ott_main_class','one_year_price', 'decimal(8,2) default 0.00');
    }

    public function down()
    {
        echo "m180907_013906_add_genre_price_list cannot be reverted.\n";
        $this->dropColumn('ott_main_class', 'one_month_price');
        $this->dropColumn('ott_main_class', 'three_month_price');
        $this->dropColumn('ott_main_class', 'six_month_price');
        $this->dropColumn('ott_main_class', 'one_year_price');
        return true;
    }

}
