<?php

namespace backend\models\Search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Mac;

/**
 * MacQuery represents the model behind the search form of `app\models\Mac`.
 */
class MacSearch extends Mac
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['MAC', 'SN', 'ver', 'regtime', 'logintime', 'duetime', 'contract_time','client_name'], 'safe'],
            [['use_flag', 'type'], 'integer'],
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
     * @param bool $all
     * @return ActiveDataProvider
     */
    public function search($params, $all = false)
    {
        $query = Mac::find();

        $query->joinWith(['detail']);

        // add conditions that should always apply here
        if ($all) {
            $condition = [
                'query' => $query,
                'pagination' => false,
                'sort' => [
                    'defaultOrder' => [
                        'MAC' => SORT_ASC
                    ]
                ]
            ];
        } else {
            $condition = [
                'query' => $query,
                'pagination' => ['pageSize' => 20],
                'sort' => [
                    'defaultOrder' => [
                        'MAC' => SORT_ASC
                    ]
                ]
            ];
        }

        $dataProvider = new ActiveDataProvider($condition);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if ($this->client_name < 0) {
            $this->client_name = null;
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'use_flag' => $this->use_flag,
            'regtime' => $this->regtime,
            'logintime' => $this->logintime,
            'type' => $this->type,
            'duetime' => $this->duetime,
            'client_id' => $this->client_name
        ]);

        $query->andFilterWhere(['like', 'mac.MAC', $this->MAC])
            ->andFilterWhere(['like', 'SN', $this->SN])
            ->andFilterWhere(['like', 'ver', $this->ver])
            ->andFilterWhere(['like', 'contract_time', $this->contract_time]);

        return $dataProvider;
    }
}
