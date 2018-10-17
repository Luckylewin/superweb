<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/7/12
 * Time: 16:20
 */

namespace console\collectors\event;


use backend\models\MajorEvent;
use backend\models\OttEvent;
use backend\models\OttEventTeam;
use common\components\BaiduTranslator;
use console\collectors\parade\CommonParade;

class common extends CommonParade
{
    /**
     * @param $eventName
     * @param $raceName
     * @param $time
     * @param $teams
     * @return MajorEvent|bool
     */
    public function createMajorEvent($eventName, $raceName, $time, $teams)
    {
        // 查找赛事类别
        $event = OttEvent::find()->where(['event_name_zh' => $eventName])->one();
        if (is_null($event)) {
            echo "没有找到赛事:" . $eventName;
            return false;
        }

        $majorEvent = new MajorEvent();

        if ($teams) {
            // 查找队伍A信息
            $teamA = OttEventTeam::find()
                ->orWhere(['event_id' => $event->id, 'team_zh_name' => $teams['teamA']])
                ->orWhere(['event_id' => $event->id, 'team_name' => $teams['teamA']])
                ->orWhere(['event_id' => $event->id, 'team_alias_name' => $teams['teamA']])
                ->one();

            if (empty($teamA)) {
                echo "找不到队伍: " . $teams['teamA'] , PHP_EOL;
                return false;
            }


            $teamB = OttEventTeam::find()
                ->orWhere(['event_id' => $event->id, 'team_zh_name' => $teams['teamB']])
                ->orWhere(['event_id' => $event->id, 'team_name' => $teams['teamB']])
                ->orWhere(['event_id' => $event->id, 'team_alias_name' => $teams['teamB']])
                ->one();

            if (empty($teamB)) {
                echo "找不到队伍: " . $teams['teamB'], PHP_EOL;
                return false;
            }

            $live_match = [
                'title' => BaiduTranslator::translate($raceName, 'zh', 'en'),
                'title_zh' => $raceName,
                'event_time' => $time,
                'event_info' => $event->event_name,
                'event_zh_info' => $event->event_name_zh,
                'event_icon' => $event->event_icon,
                'teams' => [
                    [
                        'team_name' => $teamA->team_name,
                        'team_zh_name' => $teamA->team_zh_name,
                        'team_icon' => $teamA->team_icon
                    ],
                    [
                        'team_name' => $teamB->team_name,
                        'team_zh_name' => $teamB->team_zh_name,
                        'team_icon' => $teamB->team_icon
                    ]
                ]
            ];

            $majorEvent->unique = md5( $majorEvent->base_time  . $teamA->team_name . $teamB->team_name);

        } else {
            $live_match = [
                'title' => BaiduTranslator::translate($raceName, 'zh', 'en'),
                'title_zh' => $raceName,
                'event_time' => $time,
                'event_info' => $event->event_name,
                'event_zh_info' => $event->event_name_zh,
                'event_icon' => $event->event_icon,
                'teams' => [
                ]
            ];

            $majorEvent->unique = md5( $majorEvent->base_time  . $eventName);
        }

        $majorEvent->live_match = json_encode($live_match);
        $majorEvent->title = $raceName;
        $majorEvent->time = $time;
        $majorEvent->base_time = $time;


        // 查找比赛是否存在
        $exist = MajorEvent::find()->where(['unique' => $majorEvent->unique])->exists();
        if ($exist) {
            echo "比赛 " . $raceName ." ". $teamA->team_zh_name . '-' . $teamB->team_zh_name.' 已经存在' . PHP_EOL;
            return false;
        }

        $majorEvent->save(false);
        echo "比赛 " . $raceName ." ". $teamA->team_zh_name . '-' . $teamB->team_zh_name.' 新增成功' . PHP_EOL;

        return $majorEvent;
    }
}