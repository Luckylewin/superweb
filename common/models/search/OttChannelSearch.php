<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\OttChannel;

/**
 * OttChannelSearch represents the model behind the search form of `common\models\OttChannel`.
 */
class OttChannelSearch extends OttChannel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'sub_class_id', 'sort', 'use_flag', 'channel_number'], 'integer'],
            [['name', 'zh_name', 'keywords', 'image', 'alias_name'], 'safe'],
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
        $query = OttChannel::find();
        if ($sub_id = Yii::$app->request->get('sub-id')) {
            $query->where(['sub_class_id' => $sub_id]);
        }
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'use_flag' =>SORT_DESC,
                    'channel_number' => SORT_ASC,

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
            'sub_class_id' => $this->sub_class_id,
            'sort' => $this->sort,
            'use_flag' => $this->use_flag,
            'channel_number' => $this->channel_number,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'zh_name', $this->zh_name])
            ->andFilterWhere(['like', 'keywords', $this->keywords])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'alias_name', $this->alias_name]);

        return $dataProvider;
    }
}
