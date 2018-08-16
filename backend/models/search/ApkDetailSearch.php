<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ApkDetail;

/**
 * ApkDetailQuery represents the model behind the search form of `app\models\ApkDetail`.
 */
class ApkDetailSearch extends ApkDetail
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'apk_ID', 'sort', 'is_newest'], 'integer'],
            [['type', 'ver', 'md5', 'url', 'content', 'force_update'], 'safe'],
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
    public function search($params, $query)
    {
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'is_newest' => SORT_DESC
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
            'ID' => $this->ID,
            'apk_ID' => $this->apk_ID,
            'sort' => $this->sort,
        ]);

        $query->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'ver', $this->ver])
            ->andFilterWhere(['like', 'md5', $this->md5])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'force_update', $this->force_update]);

        return $dataProvider;
    }
}
