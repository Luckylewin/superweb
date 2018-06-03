<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Parade;

/**
 * ParadeSearch represents the model behind the search form of `app\models\Parade`.
 */
class ParadeSearch extends Parade
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'channel_id'], 'integer'],
            [['channel_name', 'parade_date', 'upload_date', 'parade_data'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * @param $params
     * @param $query Model
     * @return ActiveDataProvider
     */
    public function search($params, $query)
    {
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'channel_id' => $this->channel_id,
            'parade_date' => $this->parade_date,
            'upload_date' => $this->upload_date,
        ]);

        $query->andFilterWhere(['like', 'channel_name', $this->channel_name])
            ->andFilterWhere(['like', 'parade_data', $this->parade_data]);

        return $dataProvider;
    }
}
