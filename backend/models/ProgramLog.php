<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "sys_program_log".
 *
 * @property string $date
 * @property string $local_program 本地解析前20个
 * @property string $server_program 服务器解析前二十个
 * @property string $all_program
 * @property int $all_program_sum
 */
class ProgramLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_program_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date', 'local_program', 'server_program'], 'required'],
            [['date'], 'safe'],
            [['local_program', 'server_program', 'all_program'], 'string'],
            [['all_program_sum'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'date' => 'Date',
            'local_program' => '本地解析前20个',
            'server_program' => '服务器解析前二十个',
            'all_program' => 'All Program',
            'all_program_sum' => 'All Program Sum',
        ];
    }
}
