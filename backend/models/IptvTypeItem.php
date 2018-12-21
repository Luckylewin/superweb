<?php

namespace backend\models;

use common\models\Vod;
use Yii;

/**
 * This is the model class for table "iptv_type_item".
 *
 * @property int $id
 * @property int $type_id 关联分类id
 * @property string $name 名称
 * @property string $zh_name 中文名称
 * @property int $sort 排序
 * @property int $exist_num 存在影片数量
 * @property int $is_show 是否显示
 */
class IptvTypeItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'iptv_type_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_id', 'name'], 'required'],
            [['type_id', 'sort'], 'integer'],
            [['name', 'zh_name'], 'string', 'max' => 255],
            [['exist_num', 'is_show'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'        => 'ID',
            'type_id'   => '关联分类id',
            'name'      => '名称',
            'zh_name'   => '中文名称',
            'sort'      => '排序',
            'exist_num' => '数量',
            'is_show'   => '是否显示',
        ];
    }

    public function getType()
    {

        return $this->hasOne(IptvType::className(), ['id' => 'type_id']);
    }

    public static function getTypeItems($type_id)
    {
        return self::find()
                    ->where(['type_id' => $type_id])
                    ->orderBy(['exist_num' => SORT_DESC])
                    ->all();

    }

    public function getMultiLanguage()
    {
        return $this->hasMany(MultiLang::className(), ['fid' => 'id']);
    }

    public function beforeDelete()
    {
        // 查询分类所属
        $typeParent= $this->getType()->one();
        if ($typeParent) {
            $tableName = Vod::tableName();
            $type = $this->name;

            if (isset($typeParent->vod_list_id)) {
                $list_id = $typeParent->vod_list_id;
                // 删除时把标签从影片中删除
                $sql1 = "UPDATE {$tableName} set vod_type=REPLACE(vod_type,'$type', '') where vod_cid={$list_id}";
                $sql2 = "UPDATE {$tableName} set vod_type=REPLACE(vod_type,',,', ',') where vod_cid={$list_id}";
                $sql3 = "UPDATE {$tableName} set vod_type=trim(BOTH ',' FROM `vod_type`)  where vod_cid={$list_id}";
                Yii::$app->db->createCommand($sql1)->execute();
                Yii::$app->db->createCommand($sql2)->execute();
                Yii::$app->db->createCommand($sql3)->execute();
            }
        }

        // 同时删除对应的多语言
        MultiLang::deleteAll(['fid' => $this->id, 'table' => self::tableName()]);

        return true;
    }

}
