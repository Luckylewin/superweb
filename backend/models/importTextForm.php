<?php
namespace backend\models;
use common\models\OttChannel;
use common\models\OttLink;
use common\models\SubClass;

/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/5/18
 * Time: 17:20
 */

class importTextForm extends \yii\base\Model
{
    public $text;
    public $importData = [];

    public function rules()
    {
       return [
           ['text', 'required'],
           ['text', 'checkFormat']
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
            if (count($row) == 3) {
                $this->importData[] = $row;
            }
        }

        if (empty($this->importData)) {
            $this->addError($attribute, '请用类似 “新蓝,CCTV-1,http://yf.m.l.cztv.com/channels/lantian/channel15/360p.m3u8”的格式');
        }

        return true;
    }

    public function import()
    {
        $total = 0;

        //查询是否有这个关键字的频道
        foreach ($this->importData as $data) {
            list($keyword, $name, $link) = $data;
            $subClass = SubClass::find()->where(['keyword' => $keyword])->one();
            if ($subClass) {
                //判断是否已经存在该频道
                $channel = OttChannel::find()->where([
                    'sub_class_id' => $subClass->id,
                    'name' => $name
                    ])->one();


                //不存在则新建
                if (is_null($channel)) {
                    $channel = new OttChannel();
                    $channel->sub_class_id = $subClass->id;
                    $channel->name = $name;
                    $channel->keywords = $name;
                    $channel->save(false);
                }

                //存在则判断是否有链接
                $ottLink = OttLink::find()->where(['link' => $link, 'channel_id' => $channel->id])->one();

                if (is_null($ottLink)) {
                    $ottLink = new OttLink();
                    $ottLink->channel_id = $channel->id;
                    $ottLink->link = $link;
                    $ottLink->save(false);
                    $total++;
                }

            }
        }

        return $total;
    }
}