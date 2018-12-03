<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Menu;
use common\libs\Tree;
use yii\helpers\ArrayHelper;
use yii\data\ArrayDataProvider;

/**
 * MenuSearch represents the model behind the search form about `backend\models\Menu`.
 */
class MenuSearch extends Menu
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'pid', 'display', 'sort'], 'integer'],
            [['name', 'url', 'icon_style','type'], 'safe'],
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
     * @param array $condition
     *
     * @return ActiveDataProvider | ArrayDataProvider
     */
    public function search($params, $condition = null)
    {
        $query = Menu::find();

        if ($condition) {
            $query->where($condition);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
           return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id'   => $this->id,
            'pid'  => $this->pid,
            'sort' => $this->sort,
        ]);

        $query->orderBy(['sort' => SORT_ASC]);

        $arr = $query->asArray()->all();

        $treeObj = new Tree(ArrayHelper::toArray($arr));

        $treeObj->icon = ['&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ '];
        $treeObj->nbsp = '&nbsp;&nbsp;&nbsp;';

        $dataProvider = new ArrayDataProvider([
            'allModels' => $treeObj->getGridTree(),
            'pagination' => [
                'pageSize' => 200,
            ],
        ]);

        return $dataProvider;
    }
}
