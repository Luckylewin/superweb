<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\LogOttActivity;

class LogOttActivitySearch extends LogOttActivity
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'timestamp'], 'integer'],
            [['date', 'mac', 'genre', 'code'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = LogOttActivity::find();

        if ($date = Yii::$app->request->get('date')) {
            $query->where(['date' => $date]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'date' => $this->date,
            'timestamp' => $this->timestamp,
            'code' => $this->code,
            'genre' => $this->genre,
        ]);

        return $dataProvider;
    }
}
