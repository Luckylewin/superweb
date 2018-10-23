<?php

namespace backend\models\Search;

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
            [['MAC', 'SN', 'ver', 'regtime', 'logintime', 'duetime', 'contract_time','client_name', 'client_id', 'is_online'], 'safe'],
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
            return $dataProvider;
        }

        if ($this->client_name < 0) {
            $this->client_name = null;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'use_flag' => $this->use_flag,
            'type' => $this->type,
            'duetime' => $this->duetime,
            'client_id' => $this->client_name,
            'is_online' => $this->is_online
        ]);

        if ($this->regtime) {
            $query->andFilterWhere(['>=', 'UNIX_TIMESTAMP(regtime)', strtotime($this->regtime)]);
            $query->andFilterWhere(['<', 'UNIX_TIMESTAMP(regtime)', strtotime($this->regtime) + 86400]);
        }
        
        if ($this->logintime) {
            $query->andFilterWhere(['>=', 'UNIX_TIMESTAMP(logintime)', strtotime($this->logintime)]);
            $query->andFilterWhere(['<', 'UNIX_TIMESTAMP(logintime)', strtotime($this->logintime) + 86400]);
        }

        $this->MAC = trim($this->MAC);
        $this->SN = trim($this->SN);

        $query->andFilterWhere(['like', 'mac.MAC', $this->MAC])
            ->andFilterWhere(['like', 'SN', $this->SN])
            ->andFilterWhere(['like', 'ver', $this->ver])
            ->andFilterWhere(['like', 'contract_time', $this->contract_time]);

        return $dataProvider;
    }
}
