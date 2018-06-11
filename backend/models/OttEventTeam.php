<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "ott_event_team".
 *
 * @property int $id
 * @property int $event_id 所属赛事
 * @property string $team_name 队伍名称
 * @property string $team_zh_name 队伍中文名
 * @property string $team_introduce 队伍简介
 * @property string $team_icon 队伍图标
 * @property string $team_icon_big 队伍图标
 * @property string $team_country 国家代码
 * @property string $team_info 附加属性
 * @property string $team_alias_name 别名
 */
class OttEventTeam extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ott_event_team';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event_id', 'team_name'], 'required'],
            [['event_id'], 'integer'],
            [['team_name', 'team_zh_name'], 'string', 'max' => 50],
            [['team_introduce', 'team_icon', 'team_icon_big', 'team_info', 'team_alias_name'], 'string', 'max' => 255],
            [['team_country'], 'string', 'max' => 10],
            [['id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'event_id' => '所属赛事',
            'team_name' => '队伍名称',
            'team_zh_name' => '队伍中文名',
            'team_introduce' => '队伍简介',
            'team_icon' => '队伍图标',
            'team_icon_big' => '队伍图标',
            'team_country' => '国家代码',
            'team_info' => '附加属性',
            'team_alias_name' => '别名',
        ];
    }

    static public function getDropDownList($event_id)
    {
        $data = self::find()->where(['event_id' => $event_id])->all();
        if (!empty($data)) {
            return ArrayHelper::map($data, 'id', 'team_zh_name');
        }

        return [];
    }

}
