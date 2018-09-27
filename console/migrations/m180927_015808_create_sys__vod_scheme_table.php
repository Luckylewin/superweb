<?php

use yii\db\Migration;

/**
 * Handles the creation of table `sys__vod_scheme`.
 */
class m180927_015808_create_sys__vod_scheme_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('sys__vod_scheme', [
            'vod_id' => $this->integer(11),
            'scheme_id' => $this->integer(11)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('sys__vod_scheme');
    }
}
