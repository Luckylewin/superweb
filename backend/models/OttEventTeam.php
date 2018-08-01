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
            ['team_name','check_unique'],
            [['event_id', 'team_name'], 'required'],
            [['event_id'], 'integer'],
            [['team_name', 'team_zh_name'], 'string', 'max' => 50],
            [[ 'team_icon', 'team_icon_big', 'team_info', 'team_alias_name'], 'string', 'max' => 255],
            ['team_introduce', 'string', 'max' => 800],
            [['team_country'], 'string', 'max' => 10],
            [['id'], 'unique'],
        ];
    }

    public function check_unique($attribute, $params)
    {
        if ($this->isNewRecord) {
            $result = self::find()->where(['event_id' => $this->event_id, 'team_name'=>$this->team_name])->exists();
            if ($result) {
                $this->addError($attribute, $this->team_name . '已经存在');
                return false;
            }
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'event_id' => Yii::t('backend', 'Associated event'),
            'team_name' => Yii::t('backend', 'Team Name'),
            'team_zh_name' => Yii::t('backend', 'Team Chinese Name'),
            'team_introduce' => Yii::t('backend', 'Description'),
            'team_icon' => Yii::t('backend', 'Team Icon'),
            'team_icon_big' => Yii::t('backend', 'Team Icon(big)'),
            'team_country' => Yii::t('backend', 'Country Code'),
            'team_info' => Yii::t('backend', 'Additional attribute'),
            'team_alias_name' => Yii::t('backend', 'Alias'),
        ];
    }

    static public function getDropDownList($event_id)
    {
        $data = self::find()->where(['event_id' => $event_id])->asArray()->all();
        $list = [];
        foreach ($data as $val) {
            $list[] = [
                'id' => $val['id'],
                'text' => $val['team_zh_name']
            ];
        }
        if (!empty($data)) {
            return $list;
        }

        return [];
    }

}
