<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\RenewalCard;

/**
 * RenewalCardSearch represents the model behind the search form of `backend\models\RenewalCard`.
 */
class RenewalCardSearch extends RenewalCard
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['card_num', 'card_secret', 'card_contracttime', 'is_del', 'is_valid', 'who_use'], 'safe'],
            [['created_time', 'updated_time', 'batch'], 'integer'],
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
        $query = RenewalCard::find();

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
            'created_time' => $this->created_time,
            'updated_time' => $this->updated_time,
            'batch' => $this->batch,
        ]);

        $query->andFilterWhere(['like', 'card_num', $this->card_num])
            ->andFilterWhere(['like', 'card_secret', $this->card_secret])
            ->andFilterWhere(['like', 'card_contracttime', $this->card_contracttime])
            ->andFilterWhere(['like', 'is_del', $this->is_del])
            ->andFilterWhere(['like', 'is_valid', $this->is_valid])
            ->andFilterWhere(['like', 'who_use', $this->who_use]);

        return $dataProvider;
    }
}
