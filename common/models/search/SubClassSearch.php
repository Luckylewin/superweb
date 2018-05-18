<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\SubClass;

/**
 * SubClassSearch represents the model behind the search form of `common\models\SubClass`.
 */
class SubClassSearch extends SubClass
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'main_class_id', 'sort', 'use_flag', 'created_at'], 'integer'],
            [['name', 'zh_name', 'keyword'], 'safe'],
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
     * @param null $cond
     * @return ActiveDataProvider
     */
    public function search($params, $cond = null)
    {
        $query = SubClass::find();

        $cond && $query->where($cond);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'use_flag' => SORT_DESC,
                    'sort' => SORT_ASC
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
            'main_class_id' => $this->main_class_id,
            'sort' => $this->sort,
            'use_flag' => $this->use_flag,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'zh_name', $this->zh_name])
            ->andFilterWhere(['like', 'keyword', $this->keyword]);

        return $dataProvider;
    }
}
