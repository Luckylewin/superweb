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

    public static function findByDate($date)
    {
        $model = self::find()->where(['date' => $date])->one();

        if ($model) {
            $model->all_program = json_decode($model->all_program, true);
            $model->server_program = json_decode($model->server_program, true);
        }

        return $model;
    }

    public static function findByMonth($year, $month)
    {
        $items = self::find()->where(['LIKE', 'date', $year.'-'.$month])->asArray()->all();
        $data['all_program']    = [];
        $data['server_program'] = [];

        foreach ($items as $item) {
            $all_programs = json_decode($item['all_program'], true);
            foreach ($all_programs as $program => $total) {
                if (isset($data['all_program'][$program])) {
                    $data['all_program'][$program] += $total;
                } else {
                    $data['all_program'][$program] = $total;
                }
            }

            $server_program = json_decode($item['server_program'], true);
            foreach ($server_program as $program => $total) {
                if (isset($data['server_program'][$program])) {
                    $data['server_program'][$program] += $total;
                } else {
                    $data['server_program'][$program] = $total;
                }
            }
        }

        arsort($data['all_program']);
        arsort($data['server_program']);
        $programs = ['all_program' => '', 'server_program' => ''];
        $programs = json_decode(json_encode($programs));
        $programs->all_program = array_slice($data['all_program'], 0, 20);
        $programs->server_program = array_slice($data['server_program'], 0 , 20);

        return $programs;
    }

}
