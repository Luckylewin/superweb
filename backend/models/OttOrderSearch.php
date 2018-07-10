<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\OttOrder;

/**
 * OttOrderSearch represents the model behind the search form of `backend\models\OttOrder`.
 */
class OttOrderSearch extends OttOrder
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['oid', 'expire_time'], 'integer'],
            [['uid', 'genre', 'order_num', 'is_valid'], 'safe'],
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
        $query = OttOrder::find();

        $query->joinWith(['mainOrder'])
              ->orderBy('order_addtime desc,order_ispay desc');
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 25
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'oid' => $this->oid,
            'expire_time' => $this->expire_time,
        ]);

        $query->andFilterWhere(['like', 'uid', $this->uid])
            ->andFilterWhere(['like', 'genre', $this->genre])
            ->andFilterWhere(['like', 'order_num', $this->order_num])
            ->andFilterWhere(['like', 'is_valid', $this->is_valid]);

        return $dataProvider;
    }
}
