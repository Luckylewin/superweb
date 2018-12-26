<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\VodProfiles;

/**
 * VodProfilesSearch represents the model behind the search form of `backend\models\VodProfiles`.
 */
class VodProfilesSearch extends VodProfiles
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'tmdb_id', 'douban_id', 'douban_voters'], 'integer'],
            [['name', 'alias_name', 'screen_writer', 'director', 'actor', 'area', 'language', 'type', 'tag', 'user_tag', 'plot', 'year', 'date', 'imdb_id', 'imdb_score', 'tmdb_score', 'douban_score', 'length', 'cover', 'image', 'banner', 'comment', 'fill_status', 'douban_search', 'imdb_search', 'tmdb_search', 'profile_language', 'media_type'], 'safe'],
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
        $query = VodProfiles::find();

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
            'id' => $this->id,
            'tmdb_id' => $this->tmdb_id,
            'douban_id' => $this->douban_id,
            'douban_voters' => $this->douban_voters,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'alias_name', $this->alias_name])
            ->andFilterWhere(['like', 'screen_writer', $this->screen_writer])
            ->andFilterWhere(['like', 'director', $this->director])
            ->andFilterWhere(['like', 'actor', $this->actor])
            ->andFilterWhere(['like', 'area', $this->area])
            ->andFilterWhere(['like', 'language', $this->language])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'tag', $this->tag])
            ->andFilterWhere(['like', 'user_tag', $this->user_tag])
            ->andFilterWhere(['like', 'plot', $this->plot])
            ->andFilterWhere(['like', 'year', $this->year])
            ->andFilterWhere(['like', 'date', $this->date])
            ->andFilterWhere(['like', 'imdb_id', $this->imdb_id])
            ->andFilterWhere(['like', 'imdb_score', $this->imdb_score])
            ->andFilterWhere(['like', 'tmdb_score', $this->tmdb_score])
            ->andFilterWhere(['like', 'douban_score', $this->douban_score])
            ->andFilterWhere(['like', 'length', $this->length])
            ->andFilterWhere(['like', 'cover', $this->cover])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'banner', $this->banner])
            ->andFilterWhere(['like', 'comment', $this->comment])
            ->andFilterWhere(['like', 'fill_status', $this->fill_status])
            ->andFilterWhere(['like', 'douban_search', $this->douban_search])
            ->andFilterWhere(['like', 'imdb_search', $this->imdb_search])
            ->andFilterWhere(['like', 'tmdb_search', $this->tmdb_search])
            ->andFilterWhere(['like', 'profile_language', $this->profile_language])
            ->andFilterWhere(['like', 'media_type', $this->media_type]);

        return $dataProvider;
    }
}
