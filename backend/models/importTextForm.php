<?php
namespace backend\models;
use common\models\MainClass;
use common\models\OttChannel;
use common\models\OttLink;
use common\models\SubClass;
use yii\helpers\ArrayHelper;

/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/5/18
 * Time: 17:20
 */

class importTextForm extends \yii\base\Model
{
    /**
     * 导入模式
     * @var string
     */
    public $mode;

    public $format = 'text';

    /**
     * 导入文本
     * @var string
     */
    public $text;

    /**
     * 待新增数据
     * @var array
     */
    private $importData = [];
    /**
     * @var MainClass
     */
    private $mainClass;

    /**
     * @var SubClass
     */
    private $subClass;

    /**
     * @var OttChannel
     */
    private $channel;

    /**
     * @var OttLink
     */
    private $link;

    public function rules()
    {
       return [
           ['text', 'required'],
           ['text', 'checkFormat']
       ];
    }

    public function getAttributeLabels()
    {
        return [
            'text' => '文本',
            'mode' => '模式'
        ];
    }

    public static function getMode()
    {
        return [
            'mainClass' => '一级分类,二级分类,频道名称,频道台标,链接,算法',
            'keywordChannel' => '关键字,频道名称,链接,算法',
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
            $row = preg_split('/[,]+/s', $row);
            if (count($row) >= $this->_getSpiltNumber()) {
                $this->importData[] = $row;
            }
        }

        if (empty($this->importData)) {
            $this->_setError($attribute);
        }

        return true;
    }

    private function _getSpiltNumber()
    {
        $mode = ['mainClass' => 4, 'subClass' => 3, 'channel'=> 3 , 'keywordChannel'=>3];
        if (!isset($mode[$this->mode])) {
            throw new \Exception("导入模式只支持四种 mainClass,subClass,channel,keywordChannel");
        }

        return $mode[$this->mode];
    }

    private function _setError($attribute)
    {
        switch ($this->mode)
        {
            case 'mainClass' :
                $this->addError($attribute, '请用类似 “中国(一级分类),CCTV(频道分类),CCTV-1(频道名),http://img.baidu.com/hn1.png(频道台标),http://yf.m.l.cztv.com/channels/lantian/channel15/360p.m3u8(链接),flag1(算法名称[可不加]”的格式');
                break;
            case 'subClass' :
                $this->addError($attribute, '请用类似 “新蓝(频道分类),CCTV-1(频道名),http://yf.m.l.cztv.com/channels/lantian/channel15/360p.m3u8(链接),flag1(算法名称[可不加]”的格式');
                break;
            case  'channel':
                $this->addError($attribute, '请用类似 “新蓝(频道名),CCTV-1(频道名),http://yf.m.l.cztv.com/channels/lantian/channel15/360p.m3u8(链接),flag1(算法名称[可不加]”的格式');
                break;
            default:
                $this->addError($attribute, '请用类似 “新蓝(关键字),CCTV-1(频道名),http://yf.m.l.cztv.com/channels/lantian/channel15/360p.m3u8(链接),flag1(算法名称[可不加])”的格式');
        }
    }


    /**
     * @return int
     */
    public function import()
    {
        switch ($this->mode)
        {
            case 'mainClass' :
                return $this->importViaMainClass(); break;

            default:
                return $this->importViaKeyword();
        }
    }

    private function importViaMainClass()
    {
        $total = 0;

        foreach ($this->importData as $data)
        {
            list($mainClassName, $subClassName, $channelName, $channelIcon, $link) = $data;
            //是否有算法
            $method = isset($data[5]) ? $data[5] : '';
            $scheme = isset($data[6]) ? $data[6] : 'all';
            $use_flag = isset($data[7]) ? $data[7] : OttLink::AVAILABLE;
            $decode = isset($data[8]) ? $data[8] : OttLink::SOFT_DECODE;

            $this->createMainClass($mainClassName);
            $this->createSubClass($subClassName);
            $this->createChannel($channelName, $channelIcon);
            $total = $this->createLink($link, $method, $scheme ,$use_flag, $decode, $total);
        }

        return $total;
    }

    private function importViaKeyword()
    {
        $total = 0;

        //查询是否有这个关键字的频道
        foreach ($this->importData as $data) {

            list($keyword, $name, $link) = $data;
            //是否有算法
            $method = isset($data[3]) ? $data[3] : '';
            $scheme = isset($data[4]) ? $data[4] : '';
            $use_flag = OttLink::AVAILABLE;
            $decode = OttLink::SOFT_DECODE;

            $this->subClass = SubClass::find()->where(['keyword' => $keyword])->one();

            if ($this->subClass) {
                //判断是否已经存在该频道
                $this->createChannel($name);
                //存在则判断是否有链接
                $this->createLink($link, $method, $scheme, $use_flag, $decode, $total);
            }
        }

        return $total;
    }

    private function createMainClass($mainClassName)
    {
        $mainClass = MainClass::find()->where(['name' => $mainClassName])->one();
        //不存在新建
        if (is_null($mainClass)) {
            $mainClass = new MainClass();
            $mainClass->name = $mainClassName;
            $mainClass->save(false);
        }
        $this->mainClass = $mainClass;
    }

    /**
     * @param $subClassName
     */
    private function createSubClass($subClassName)
    {
        //存在则下一步
        $subClass = SubClass::find()->where(['name' => $subClassName, 'main_class_id' => $this->mainClass->id])->one();
        if ($this->mainClass && is_null($subClass)) {
            $subClass = new SubClass();
            $subClass->name = $subClassName;
            $subClass->main_class_id = $this->mainClass->id;
            $subClass->save(false);
        }

        $this->subClass = $subClass;
    }

    private function createChannel($name, $icon = null)
    {
        $channel = OttChannel::find()->where(['sub_class_id' => $this->subClass->id, 'name' => $name])->one();

        //不存在则新建
        if ($this->subClass && is_null($channel)) {
            $channel = new OttChannel();
            $channel->sub_class_id = $this->subClass->id;
            $channel->name = $name;
            $channel->keywords = $name;
            if ($icon && $icon != 'null')  $channel->image = $icon;
            $channel->save(false);
        } elseif ($channel->image != $icon && !is_null($icon)) {
            $channel->image = ($icon == 'null' ? '' : $icon);
            $channel->save(false);
        }

        $this->channel = $channel;
    }


    private function createLink($link, $method, $scheme, $use_flag, $decode ,&$total)
    {
        $ottLink = OttLink::find()->where(['link' => $link, 'channel_id' => $this->channel->id])->one();

        if ($this->channel && is_null($ottLink)) {
            $ottLink = new OttLink();
            $ottLink->channel_id = $this->channel->id;
            $ottLink->link = $link;
            $ottLink->method = $method;
            $ottLink->use_flag = $use_flag;
            $ottLink->decode = $decode;

            if ($scheme != 'all') {
                $scheme = explode('|', trim($scheme));
                if (!empty($scheme)) {
                    $scheme = Scheme::find()->where(['not in', 'schemeName', $scheme])->asArray()->all();
                    $scheme = implode(',', ArrayHelper::getColumn($scheme ,'id'));
                    $ottLink->scheme_id = $scheme;
                }
            }

            $ottLink->save(false);
            $total++;
        }

        return $total;
    }
}