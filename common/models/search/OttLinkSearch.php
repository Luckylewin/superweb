<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\OttLink;

/**
 * OttLinkSearch represents the model behind the search form of `common\models\OttLink`.
 */
class OttLinkSearch extends OttLink
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'channel_id', 'sort', 'format'], 'integer'],
            [['link', 'source', 'use_flag', 'script_deal', 'definition', 'method', 'decode'], 'safe'],
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
        $query = OttLink::find();

        // add conditions that should always apply here
        if ($channel_id = Yii::$app->request->get('channel_id')) {
            $query->where(['channel_id' => $channel_id]);
        }
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
            'id' => $this->id,
            'channel_id' => $this->channel_id,
            'sort' => $this->sort,
            'format' => $this->format,
        ]);

        $query->andFilterWhere(['like', 'link', $this->link])
            ->andFilterWhere(['like', 'source', $this->source])
            ->andFilterWhere(['like', 'use_flag', $this->use_flag])
            ->andFilterWhere(['like', 'script_deal', $this->script_deal])
            ->andFilterWhere(['like', 'definition', $this->definition])
            ->andFilterWhere(['like', 'method', $this->method])
            ->andFilterWhere(['like', 'decode', $this->decode]);

        return $dataProvider;
    }
}
