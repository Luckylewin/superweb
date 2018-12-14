<?php

namespace backend\controllers;

use Yii;
use common\models\VodList;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * VodListController implements the CRUD actions for VodList model.
 */
class VodListController extends BaseController
{

    /**
     * Lists all VodList models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => VodList::find(),
            'sort' => [
                'defaultOrder' => [
                    'list_sort' => SORT_ASC
                ]
            ]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
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
        $model = new VodList();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setFlash('success', Yii::t('backend', 'Success'));
            return $this->redirect(Url::to(['vod-list/index']));
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setFlash('success', Yii::t('backend', 'Success'));
            return $this->goBack($this->getReferer());
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }


    protected function findModel($id)
    {
        if (($model = VodList::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
