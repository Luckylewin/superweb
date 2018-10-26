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

    public function attributeLabels()
    {
        return [
            'text' => '导入数据',
            'mode' => '模式'
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
            $columns = [];
            foreach ($row as $column) {
                $columns[] = strstr($column, ':', true);
            }
            // 判断是否存在一级分类 二级分类 频道名称 链接
            $diff = array_diff(['一级分类', '二级分类', '频道名称', '链接'], $columns);
            if (empty($diff)) {
                $this->importData[] = $row;
            }
        }

        if (empty($this->importData)) {
            $this->_setError($attribute);
        }

        return true;
    }


    private function _setError($attribute)
    {
        $this->addError($attribute, '请遵循格式，否则无法导入');
    }

    public function import()
    {
        $total = 0;

        foreach ($this->importData as $data)
        {
            $mainClassName = '';
            $subClassName  = '';
            $channelName   = '';
            $channelIcon   = '';
            $channelSort   = '0';
            $link          = '';
            $method        = 'null';
            $scheme        = 'all';
            $use_flag      = 1;
            $decode        = 1;
            $link_sort     = 'null';

// 1) 一级分类:vn,二级分类:vn,频道名称:vtv1,频道排序:12,频道可用:1,链接:http://topthinker.oss-cn-hongkong.aliyuncs.com/channel/5b25dd9c859a2.png,解码方式:硬解,链接排序:2,链接可用:1,算法标志:null,指定方案号:rk323|rk324|dvb|6605s

        foreach ($data as $column) {
                $key   = trim(strstr($column, ':', true));
                $value = trim(ltrim(strstr($column, ':'), ':'));

                switch ($key)
                {
                    case '一级分类':
                        $mainClassName = $value;
                        break;
                    case '二级分类':
                        $subClassName = $value;
                        break;
                    case '频道名称':
                        $channelName = $value;
                        break;
                    case '频道图标':
                        $channelIcon  = $value;
                        break;
                    case '频道排序':
                        $channelSort = $value;
                        break;
                    case '链接':
                        $link = $value;
                        break;
                    case '解码方式':
                        if (is_numeric($value) == false) {
                            $value = $value == '硬解' ? 1 : 0;
                        }
                        $decode = $value;
                        break;
                    case '链接排序':
                        $link_sort = $value;
                        break;
                    case '链接可用':
                        $use_flag = $value;
                        break;
                    case '算法标志':
                        $method = $value;
                        break;
                    case '不支持方案号':
                        $scheme = $value;
                        break;
                }
            }

            $this->createMainClass($mainClassName);
            $this->createSubClass($subClassName);
            $this->createChannel($channelName, $channelIcon, $channelSort);
            $total = $this->createLink($link, $method, $scheme ,$use_flag, $decode,$link_sort, $total);

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

    private function createChannel($name, $icon = null, $channelSort = 0)
    {
        $channel = OttChannel::find()->where(['sub_class_id' => $this->subClass->id, 'name' => $name])->one();

        //不存在则新建
        if ($this->subClass && is_null($channel)) {
            $channel = new OttChannel();
            $channel->sub_class_id = $this->subClass->id;
            $channel->name = $name;
            $channel->keywords = $name;
            $channel->zh_name = $name;
            $channel->sort = $channelSort;
            if ($icon && $icon != 'null')  $channel->image = $icon;
            $channel->save(false);
        } elseif ($channel->image != $icon && !is_null($icon)) {
            $channel->image = ($icon == 'null' ? '' : $icon);
            $channel->save(false);
        } else if ($channel->sort != $channelSort) {
            $channel->sort = $channelSort;
            $channel->save(false);
        }

        $this->channel = $channel;
    }


    private function createLink($link, $method, $scheme, $use_flag, $decode, $link_sort ,&$total)
    {
        $ottLink = OttLink::find()->where(['link' => $link, 'channel_id' => $this->channel->id])->one();
        $max = OttLink::find()->where(['link' => $link, 'channel_id' => $this->channel->id])->max('sort');
        $link_sort = $link_sort == 'null' ? $max + 1 : $link_sort;

        if ($this->channel && is_null($ottLink)) {
            $ottLink = new OttLink();
            $ottLink->channel_id = $this->channel->id;
            $ottLink->link = $link;
            $ottLink->method = $method;
            $ottLink->use_flag = $use_flag;
            $ottLink->decode = $decode;
            $ottLink->sort = $link_sort;

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
        } else {

            if($ottLink->use_flag != $use_flag)  $ottLink->use_flag = $use_flag;
            if($ottLink->sort != $link_sort)  $ottLink->sort = $link_sort;
            if($ottLink->decode != $decode)  $ottLink->decode = $decode;
            if($ottLink->method != $method)  $ottLink->method = $method;

            $ottLink->save(false);
        }

        return $total;
    }
}