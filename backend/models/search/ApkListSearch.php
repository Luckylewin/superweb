<?php

namespace backend\models\search;

use backend\models\Admin;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ApkList;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

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
        // 判断身份
        if (Yii::$app->user->getIdentity()->username != Admin::SUPER_ADMIN) {
            $user = Admin::findOne(Yii::$app->user->getId());
            $scheme_id = ArrayHelper::getColumn($user->getScheme('id'), 'id');

            $query = ApkList::find()->joinWith([
                'schemes' => function($query) use ($scheme_id) {
                    /**
                     * @var $query ActiveQuery
                     */
                    $query->andWhere(['IN', 'sys_scheme.id', $scheme_id]);
                }
            ]);

        } else {
            $query = ApkList::find();
        }
        

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
