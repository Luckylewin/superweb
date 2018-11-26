<?php

namespace backend\controllers;

use Yii;
use backend\models\IptvTypeItem;
use common\components\Func;
use common\models\Type;
use common\models\Vod;
use common\models\VodList;
use backend\models\IptvType;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * IptvTypeController implements the CRUD actions for IptvType model.
 */
class IptvTypeController extends BaseController
{
    public $list;

    public function actionIndex()
    {
        $vod_list_id = Yii::$app->request->get('list_id');

        if ($vod_list_id == false) {
            return $this->redirect(Url::to(['vod-list/index']));
        }

        $this->session()->set('vod_list_id', $vod_list_id);

        $this->list = VodList::findOne($vod_list_id);

        if (is_null($this->list)) {
            throw new NotFoundHttpException(Yii::t('backend', '404 Not Found'));
        }

        $dataProvider = new ActiveDataProvider([
            'query' => IptvType::find()->where(['vod_list_id' => $vod_list_id]),
            'sort' => [
                'defaultOrder' => [
                    'sort' => SORT_ASC
                ]
            ]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'list' => $this->list
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $this->rememberReferer();
        $model = new IptvType();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->success();
            return $this->redirect(Func::getLastPage());
        }

        $model->vod_list_id = Yii::$app->request->get('vod_list_id');

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setFlash('success', Yii::t('backend', 'success'));
            if ($this->session()->has('vod_list_id')) {
                return $this->redirect(['iptv-type/index', 'list_id' => $this->session()->get('vod_list_id')]);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        $this->success('info');
        return $this->redirect($this->getReferer());
    }

    protected function findModel($id)
    {
        if (($model = IptvType::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public function actionSync()
    {
        $vod_list_id = Yii::$app->request->get('vod_list_id');


        $itemField = [
            'year' => 'vod_year',
            'type' => 'vod_type',
            'language' => 'vod_language',
            'area' => 'vod_area'
        ];

        foreach ($itemField as $key => $field) {
            // 查找 vod
            $typeList = IptvType::find()->with('items')->where(['vod_list_id' => $vod_list_id, 'field' => $key])->asArray()->one();

            if ($typeList) {
                // 新增不存在的分类标签
                $existTypes = Vod::find()->select($field)->where(['vod_cid' => $vod_list_id])->distinct()->column();
                $types = [];
                foreach ($existTypes as $existType) {
                    $types = array_merge($types, explode(',', $existType));
                }
                $types = array_filter(array_unique($types));
                $category = array_column($typeList['items'], 'name');

                if (!empty($types)) {
                    foreach ($types as $type) {
                        if (in_array($type, $category) == false) {
                            // 新增
                            $typeItem = new IptvTypeItem();
                            $typeItem->name     = $type;
                            $typeItem->zh_name  = $type;
                            $typeItem->sort     = 0 ;
                            $typeItem->type_id  = $typeList['id'];

                            $typeItem->save(false);
                        }
                    }
                }
            }
        }

        $items = IptvType::find()->with('items')->where(['vod_list_id' => $vod_list_id])->asArray()->all();
        foreach ($items as $item) {
            $items = $item['items'];
            foreach ($items as $val) {
                if (strtolower($item['field']) == 'hot') $item['field'] = 'type';

                $count = Vod::find()
                            ->where(['vod_cid' => $vod_list_id])
                            ->andFilterWhere(['like', 'vod_'.$item['field'], $val['name']])
                            ->count();

                IptvTypeItem::updateAll(['exist_num' => $count], ['id' => $val['id']]);
            }
        }

        $this->success();
        return $this->redirect($this->getReferer());
    }

    public function actionSetLanguage($id)
    {
        $model = VodList::findOne(['list_id' => $id]);
        if ($model->load($this->getRequest()->post())) {
            VodList::updateAll(['supported_language' => json_encode($model->supported_language)], [
                '>', 'list_id', 0
            ]);

            $this->success();

            return $this->redirect($this->getReferer());
        }

        $model->supported_language = json_decode($model->supported_language);
        return $this->render('set-language', [
            'model' => $model
        ]);
    }

}
