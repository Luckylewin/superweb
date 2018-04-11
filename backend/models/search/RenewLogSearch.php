<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use backend\models\RenewLog;
use yii\data\ActiveDataProvider;


/**
 * RenewLogQuery represents the model behind the search form of `app\models\RenewLog`.
 */
class RenewLogQuery extends RenewLog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'date'], 'integer'],
            [['mac', 'renew_period', 'card_num', 'renew_operator'], 'safe'],
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
        $query = RenewLog::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'date' => SORT_DESC
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
            'id' => $this->id,
            'date' => $this->date,
        ]);

        $query->andFilterWhere(['like', 'mac', $this->mac])
            ->andFilterWhere(['like', 'renew_period', $this->renew_period])
            ->andFilterWhere(['like', 'card_num', $this->card_num])
            ->andFilterWhere(['like', 'renew_operator', $this->renew_operator]);

        return $dataProvider;
    }
}
