<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\OttAccess;

/**
 * OttAccessSearch represents the model behind the search form of `backend\models\OttAccess`.
 */
class OttAccessSearch extends OttAccess
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mac', 'genre', 'deny_msg', 'access_key'], 'safe'],
            [['is_valid', 'expire_time'], 'integer'],
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
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = OttAccess::find()->with('order');

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
            'is_valid' => $this->is_valid,
            'expire_time' => $this->expire_time,
        ]);

        $query->andFilterWhere(['like', 'mac', $this->mac])
            ->andFilterWhere(['like', 'genre', $this->genre])
            ->andFilterWhere(['like', 'deny_msg', $this->deny_msg])
            ->andFilterWhere(['like', 'access_key', $this->access_key]);

        return $dataProvider;
    }
}
