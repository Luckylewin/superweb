<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "sys_timeline_log".
 *
 * @property int $id
 * @property string $year
 * @property string $date
 * @property string $total_line
 * @property string $watch_line
 * @property string $token_line
 * @property string $local_watch_line
 * @property string $server_watch_line 服务器解析
 */
class TimelineLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_timeline_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date', 'total_line', 'token_line'], 'required'],
            [['date'], 'safe'],
            [['year'], 'string', 'max' => 255],
            [['total_line', 'watch_line', 'token_line', 'local_watch_line', 'server_watch_line'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'year' => 'Year',
            'date' => 'Date',
            'total_line' => 'Total Line',
            'watch_line' => 'Watch Line',
            'token_line' => 'Token Line',
            'local_watch_line' => 'Local Watch Line',
            'server_watch_line' => '服务器解析',
        ];
    }
}
