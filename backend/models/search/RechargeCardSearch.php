<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\RechargeCard;

/**
 * RechargeCardQuery represents the model behind the search form of `app\models\RechargeCard`.
 */
class RechargeCardSearch extends RechargeCard
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['card_num', 'card_secret', 'card_contracttime', 'is_valid', 'is_del', 'is_use', 'create_time'], 'safe'],
            [['batch'], 'integer'],
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
        $query = RechargeCard::find();

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
            'create_time' => $this->create_time,
            'batch' => $this->batch,
        ]);

        $query->andFilterWhere(['like', 'card_num', $this->card_num])
            ->andFilterWhere(['like', 'card_secret', $this->card_secret])
            ->andFilterWhere(['like', 'card_contracttime', $this->card_contracttime])
            ->andFilterWhere(['like', 'is_valid', $this->is_valid])
            ->andFilterWhere(['like', 'is_del', $this->is_del])
            ->andFilterWhere(['like', 'is_use', $this->is_use]);

        return $dataProvider;
    }
}
