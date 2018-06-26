<?php

namespace common\models\search;

use common\models\VodList;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Vod;
use yii\data\Sort;

/**
 * VodSearch represents the model behind the search form of `common\models\Vod`.
 */
class VodSearch extends Vod
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vod_id', 'vod_cid', 'vod_year', 'vod_total', 'vod_addtime', 'vod_filmtime', 'vod_hits', 'vod_hits_day', 'vod_hits_week', 'vod_hits_month', 'vod_up', 'vod_down', 'vod_price', 'vod_trysee', 'vod_golder', 'vod_length', 'vod_copyright', 'vod_douban_id'], 'integer'],
            [['vod_name', 'vod_ename', 'vod_title', 'vod_keywords', 'vod_type', 'vod_actor', 'vod_director', 'vod_content', 'vod_pic', 'vod_pic_bg', 'vod_pic_slide', 'vod_area', 'vod_language', 'vod_continu', 'vod_isend', 'vod_stars', 'vod_status', 'vod_ispay', 'vod_play', 'vod_server', 'vod_url', 'vod_inputer', 'vod_reurl', 'vod_jumpurl', 'vod_letter', 'vod_skin', 'vod_weekday', 'vod_series', 'vod_state', 'vod_version', 'vod_scenario'], 'safe'],
            [['vod_gold', 'vod_douban_score'], 'number'],
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
        $query = Vod::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'vod_id' => SORT_DESC
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if ($this->vod_cid) {
            $list = VodList::findOne($this->vod_cid);

            if (in_array(strtolower($list->list_dir),['shouye', 'index', 'recommend'])) {
                $query->andFilterWhere(['vod_home' => 1]);
                unset($this->vod_cid);
            }

        }

        // grid filtering conditions
        $query->andFilterWhere([
            'vod_id' => $this->vod_id,
            'vod_cid' => $this->vod_cid,
            'vod_year' => $this->vod_year,
            'vod_total' => $this->vod_total,
            'vod_addtime' => $this->vod_addtime,
            'vod_filmtime' => $this->vod_filmtime,
            'vod_hits' => $this->vod_hits,
            'vod_hits_day' => $this->vod_hits_day,
            'vod_hits_week' => $this->vod_hits_week,
            'vod_hits_month' => $this->vod_hits_month,
            'vod_up' => $this->vod_up,
            'vod_down' => $this->vod_down,
            'vod_price' => $this->vod_price,
            'vod_trysee' => $this->vod_trysee,
            'vod_gold' => $this->vod_gold,
            'vod_golder' => $this->vod_golder,
            'vod_length' => $this->vod_length,
            'vod_copyright' => $this->vod_copyright,
            'vod_douban_id' => $this->vod_douban_id,
            'vod_douban_score' => $this->vod_douban_score,
            'vod_stars' => $this->vod_stars,
            'vod_ispay' => $this->vod_ispay,
            'vod_status' => $this->vod_status,
            'vod_isend' => $this->vod_isend,
            'vod_weekday' => $this->vod_weekday,
        ]);

        $query->andFilterWhere(['like', 'vod_name', $this->vod_name])
            ->andFilterWhere(['like', 'vod_ename', $this->vod_ename])
            ->andFilterWhere(['like', 'vod_title', $this->vod_title])
            ->andFilterWhere(['like', 'vod_keywords', $this->vod_keywords])
            ->andFilterWhere(['like', 'vod_type', $this->vod_type])
            ->andFilterWhere(['like', 'vod_actor', $this->vod_actor])
            ->andFilterWhere(['like', 'vod_director', $this->vod_director])
            ->andFilterWhere(['like', 'vod_area', $this->vod_area])
            ->andFilterWhere(['like', 'vod_language', $this->vod_language])
            ->andFilterWhere(['like', 'vod_url', $this->vod_url])
            ->andFilterWhere(['like', 'vod_letter', $this->vod_letter])
            ->andFilterWhere(['like', 'vod_series', $this->vod_series])
            ->andFilterWhere(['like', 'vod_state', $this->vod_state])
            ->andFilterWhere(['like', 'vod_version', $this->vod_version])
            ->andFilterWhere(['like', 'vod_scenario', $this->vod_scenario]);

        return $dataProvider;
    }
}
