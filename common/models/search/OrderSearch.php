<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Order;

/**
 * OrderSearch represents the model behind the search form of `common\models\Order`.
 */
class OrderSearch extends Order
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'order_uid', 'order_total', 'order_addtime', 'order_paytime', 'order_confirmtime'], 'integer'],
            [['order_sign', 'order_status', 'order_ispay', 'order_info', 'order_paytype'], 'safe'],
            [['order_money'], 'number'],
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
        $query = Order::find()->where(['del_flag' => 0]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'order_addtime' => SORT_DESC,
                    'order_ispay' => SORT_DESC
                ]
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
            'order_id' => $this->order_id,
            'order_uid' => $this->order_uid,
            'order_total' => $this->order_total,
            'order_money' => $this->order_money,
            'order_addtime' => $this->order_addtime,
            'order_paytime' => $this->order_paytime,
            'order_confirmtime' => $this->order_confirmtime,
        ]);

        $query->andFilterWhere(['like', 'order_sign', $this->order_sign])
            ->andFilterWhere(['like', 'order_status', $this->order_status])
            ->andFilterWhere(['like', 'order_ispay', $this->order_ispay])
            ->andFilterWhere(['like', 'order_info', $this->order_info])
            ->andFilterWhere(['like', 'order_paytype', $this->order_paytype]);

        return $dataProvider;
    }
}
