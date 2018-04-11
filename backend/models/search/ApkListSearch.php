<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ApkList;

/**
 * ApkListQuery represents the model behind the search form of `app\models\ApkList`.
 */
class ApkListSearch extends ApkList
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'sort'], 'integer'],
            [['typeName', 'type', 'class',  'scheme_id'], 'safe'],
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
        $query = ApkList::find();

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
            'ID' => $this->ID,
            'sort' => $this->sort,
        ]);

        $query->andFilterWhere(['like', 'typeName', $this->typeName])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'class', $this->class])
            ->andFilterWhere(['like', 'scheme_id', $this->scheme_id]);

        return $dataProvider;
    }
}
