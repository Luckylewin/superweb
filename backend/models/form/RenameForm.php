<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/11/26
 * Time: 10:28
 */

namespace backend\models\form;

use common\models\Vod;
use Yii;
use backend\models\IptvType;
use backend\models\IptvTypeItem;
use yii\base\Model;

class RenameForm extends Model
{
    public $oldName;

    public $name;

    public $id;

    public function rules()
    {
        return [
            ['name', 'required'],
            ['name', 'notEqual'],
            ['oldName', 'string'],
            ['id', 'integer']
        ];
    }

    public function notEqual($attribute, $value)
    {
        if ($this->name == $this->oldName) {
            $this->addError($attribute, '不能与旧名称一致');
            return false;
        }
    }

    public function attributeLabels()
    {
        return [
            'oldName' => '旧的分类名称',
            'name' => '新的分类名称'
        ];
    }

    public function rename()
    {
        $this->validate();

        // 查找它的分类
        $item = IptvTypeItem::find()->with('type')->where(['id' => $this->id])->asArray()->one();

        if (!empty($item['type'])) {
            $field = $item['type']['field'];
            if (in_array($field, ['hot', 'year', 'area', 'type', 'language'])) {
                // 把原来分类下分类重命名
                $list_id = $item['type']['vod_list_id'];

                switch ($field)
                {
                    case 'year';
                          $sql = "UPDATE iptv_vod set vod_year='{$this->name}' where vod_year like '%{$this->oldName}%' ";
                          break;
                    case 'type';
                    case 'hot';
                          $sql = "UPDATE iptv_vod set vod_type=REPLACE(vod_type,'{$this->oldName}','{$this->name}') where vod_type LIKE '%{$this->oldName}%'";
                          break;
                    case 'area';
                          $sql = "UPDATE iptv_vod set vod_area='{$this->name}' where vod_area like '%{$this->oldName}%'";
                          break;
                    case 'language';
                          $sql = "UPDATE iptv_vod set vod_language='{$this->name}' where vod_language like '%{$this->oldName}%'";
                          break;
                }

                Yii::$app->db->createCommand($sql)->execute();
            }
        }

        return true;
    }
}