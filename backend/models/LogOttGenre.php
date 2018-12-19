<?php

namespace backend\models;

use common\models\MainClass;
use Yii;

/**
 * This is the model class for table "log_ott_genre".
 *
 * @property int $id
 * @property string $date
 * @property string $genre
 * @property int $download_time
 * @property int $person_time
 * @property int $same_version_time
 */
class LogOttGenre extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log_ott_genre';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date'], 'safe'],
            [['download_time', 'person_time', 'same_version_time'], 'integer'],
            [['genre'], 'string', 'max' => 30],
        ];
    }

    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            if (self::find()->where(['date' => $this->date,'genre' => $this->genre])->exists()) {
                return false;
            }
        }

        return true;
    }

    // 日期统计
    static public function findByDate($date)
    {
        $data = [];

        $genres = MainClass::find()->all();
        foreach ($genres as $genre) {
            if ($genre->list_name) {
                $logs  = self::find()
                    ->where(['genre' => $genre->list_name,'date' => $date])
                    ->select(["download_time" ,"person_time","same_version_time"])
                    ->asArray()
                    ->all();

                foreach ($logs as $log) {
                    if ($log['download_time']) {
                        $data[$genre->list_name] = $log;
                    }
                }
            }
        }

        return $data;
    }

    // 按月份查询
    public static function findByMonth($year, $month)
    {
        $data = [];

        $genres = MainClass::find()->all();
        foreach ($genres as $genre) {
           $temp  = self::find()
                ->where(['genre' => $genre->name])
                ->andFilterWhere(['like', 'date', $year.'-'.$month])
                ->select([
                            "download_time" => "sum(download_time)",
                            "person_time" => "sum(person_time)",
                            "same_version_time" => "sum(same_version_time)"
                ])
                ->asArray()
                ->one();

           if ($temp['download_time']) {
               $data[$genre->name] = $temp;
           }

        }

        return $data;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'genre' => 'Genre',
            'download_time' => 'Download Time',
            'person_time' => 'Person Time',
            'same_version_time' => 'Same Version Time',
        ];
    }
}
