<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/6/1
 * Time: 16:03
 */

namespace backend\models\form;


use backend\models\Mac;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use Yii;

class ImportMacForm extends Model
{
    public $format = 'text';

    /**
     * 导入文本
     * @var string
     */
    public $text;

    /**
     * @var int 客户id
     */
    public $client_id;

    /**
     * @var int 开通时间
     */
    public $contract_time;

    /**
     * @var string 时间单位
     */
    public $unit;

    /**
     * @var array
     */
    private $importData;

    public function rules()
    {
        return [
            [['text', 'client_id', 'contract_time'], 'required'],
            ['contract_time', 'integer'],
            ['unit', 'safe'],
            ['text', 'checkFormat'],
            ['text', 'checkUnique']
        ];
    }

    public function checkFormat($attribute, $params)
    {
        //判断第一行是否被,分成了三部分
        $rows = preg_split('/[;\r\n]+/s', $this->$attribute);
        if (empty($rows)) {
            $this->addError($attribute, '行与行之间请用enter换行分隔');
            return false;
        }

        foreach ($rows as $row) {
            $row = preg_split('/[,\|]+/s', $row);
            if (count($row) == 2) {
                $this->importData[] = [
                    'mac' => $row[0],
                    'sn' => $row[1]
                ];
            }
        }

        if (empty($this->importData)) {
            $this->addError($attribute, "似乎格式错误了 :(");
            return false;
        }
        return true;
    }

    public function checkUnique($attribute, $params)
    {
        $macs = ArrayHelper::getColumn($this->importData, 'mac');
        //查询哪些已经重复了
        $existMac = Mac::find()->select('MAC')->where(['in', 'MAC', $macs])->asArray()->all();

        if (!is_null($existMac)) {
            $existMac = ArrayHelper::getColumn($existMac, 'MAC');
            $existMac = implode(',', $existMac);
            $this->addError($attribute, "数据库已经存在MAC：" . $existMac);
            return false;
        }

        return true;
    }

    public function attributeLabels()
    {
        return [
            'text' => '导入文本',
            'mode' => '模式',
            'client_id' => '绑定客户',
            'contract_time' => '开通时长'
        ];
    }

    public function import()
    {
        $this->validate();
        $macData = [];
        $macDetailData = [];

        $macFields = ['MAC', 'SN', 'contract_time', 'regtime'];
        $macDetailFields = ['MAC', 'client_id'];

        foreach ($this->importData as $value) {
            $macData[] = [$value['mac'], $value['sn'], $this->contract_time . " " . $this->unit, date('Y-m-d H:i:s')];
            $macDetailData[] = [$value['mac'], $this->client_id];
        }

        $total = Yii::$app->db->createCommand()->batchInsert('mac', $macFields, $macData)->execute();
        Yii::$app->db->createCommand()->batchInsert('mac_detail', $macDetailFields, $macDetailData)->execute();

        return $total;
    }

}