<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/9/7
 * Time: 15:43
 */

namespace backend\models\form;


use backend\models\AppLocker;
use backend\models\Mac;
use yii\base\Model;

class LockerSwitchForm extends Model
{
    public $app_name;
    public $mac;
    public $switch;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mac','switch', 'app_name'], 'required']
        ];
    }

    public function attributeLabels()
    {
        return [
            'type' => 'mac地址'
        ];
    }

    /**
     * 锁定
     * @return bool
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function deal()
    {
        $mac = preg_split('/\r\n/', $this->mac);
        if (empty($this->mac)) {
            $this->addError('mac', '不能为空');
        }

        if ($this->switch == 'off') {
            $data = [];
            foreach ($mac as $value) {
                $existLocker = AppLocker::find()->where(['mac' => $value, 'app_name' => $this->app_name])->exists();
                $existMac = Mac::find()->where(['MAC' => $value])->exists();

                if ($existLocker === false && $existMac == true) {
                    $data[] = [$value, $this->app_name, 'off'];
                }
            }

            \Yii::$app->db->createCommand()->batchInsert('app_locker_switcher', ['mac', 'app_name', 'switch'], $data)->execute();
        } else {
            $data = $mac;
            foreach ($data as $value) {
                $locker = AppLocker::find()->where(['mac' => $value, 'app_name' => $this->app_name])->limit(1)->one();
                if ($locker) {
                    $locker->delete();
                }
            }
        }

        return count($data);
    }
}