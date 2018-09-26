<?php

use yii\db\Migration;

/**
 * Handles the creation of table `log_statics`.
 */
class m180925_095729_create_log_statics_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('log_tmp_interface', [
            'header' => $this->string(20),
            'mac' => $this->string(32)
        ]);

        $this->createTable('log_statics', [
            'id' => $this->primaryKey(),
            'date' => $this->string(10)->comment('日期'),
            'active_user' => $this->integer(7)->defaultValue(0)->defaultValue(0)->comment('活跃用户'),
            'valid_user' => $this->integer(7)->defaultValue(0)->comment('有效用户'),
            'total' => $this->integer(7)->defaultValue(0)->comment('请求总数'),
            'token' => $this->integer(7)->defaultValue(0)->comment('token请求总数'),
            'ott_list' => $this->integer(7)->defaultValue(0)->comment('ott节目列表请求总数'),
            'iptv_list' => $this->integer(7)->defaultValue(0)->comment('iptv节目列表请求总数'),
            'karaoke_list' => $this->integer(7)->defaultValue(0)->comment('卡拉ok节目列表'),
            'epg' => $this->integer(7)->defaultValue(0)->comment('预告列表请求总数'),
            'app_upgrade' => $this->integer(7)->defaultValue(0)->comment('APP升级'),
            'firmware_upgrade' => $this->integer(7)->defaultValue(0)->comment('固件升级'),
            'renew' => $this->integer(7)->defaultValue(0)->comment('会员续费'),
            'dvb_register' => $this->integer(7)->defaultValue(0)->comment('dvb注册'),
            'ott_charge' => $this->integer(7)->defaultValue(0)->comment('ott分类'),
            'pay' => $this->integer(7)->defaultValue(0)->comment('支付接口'),
            'activateGenre' => $this->integer(7)->defaultValue(0)->comment('激活分类使用'),
            'paypal_callback' => $this->integer(7)->defaultValue(0)->comment('paypal 异步通知'),
            'dokypay_callback' => $this->integer(7)->defaultValue(0)->comment('dokypay 异步通知'),
            'getServerTime' => $this->integer(7)->defaultValue(0)->comment('服务器时间'),
            'play' => $this->integer(7)->defaultValue(0)->comment('播放接口'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('log_tmp_interface');
        $this->dropTable('log_statics');
        return true;
    }
}
