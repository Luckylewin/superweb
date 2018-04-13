<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Karaoke;

/**
 * AlbumNameKaraokeQuery represents the model behind the search form of `app\models\AlbumNameKaraoke`.
 */
class KaraokeSearch extends Karaoke
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'tid', 'year', 'flag', 'hit_count', 'voole_id', 'price', 'is_finish', 'yesterday_viewed', 'download_flag'], 'integer'],
            [['albumName', 'albumImage', 'mainActor', 'directors', 'tags', 'info', 'area', 'keywords', 'wflag', 'mod_version', 'updatetime', 'totalDuration', 'utime', 'url', 'act_img', 'is_del'], 'safe'],
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
        $query = Karaoke::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'hit_count' => SORT_DESC,
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
            'area' => $this->area,
            'year' => $this->year,
            'hit_count' => $this->hit_count,
            'is_del' => $this->is_del,
        ]);

        $query
            ->andFilterWhere(['like', 'mainActor', $this->mainActor])
            ->andFilterWhere(['like', 'directors', $this->directors])
            ->andFilterWhere(['like', 'tags', $this->tags])
            ->andFilterWhere(['like', 'info', $this->info])
            ->andFilterWhere(['like', 'area', $this->area])
            ->andFilterWhere(['like', 'keywords', $this->keywords])
            ->andFilterWhere(['like', 'wflag', $this->wflag])
            ->andFilterWhere(['like', 'mod_version', $this->mod_version])
            ->andFilterWhere(['like', 'totalDuration', $this->totalDuration])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'act_img', $this->act_img]);

        return $dataProvider;
    }
}
