<?php

namespace backend\controllers;

use backend\models\Admin;
use backend\models\form\LockerSwitchForm;
use Yii;
use backend\models\ApkList;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use backend\models\search\ApkListSearch;

/**
 * ApkListController implements the CRUD actions for ApkList model.
 */
class ApkListController extends BaseController
{

    public function actionIndex()
    {
        $searchModel = new ApkListSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ApkList();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->success('success', Yii::t('backend', 'Success'));
            return $this->redirect(['view', 'id' => $model->ID]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->success();
            return $this->redirect(['view', 'id' => $model->ID]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        $this->setFlash('success', Yii::t('backend', 'Success'));
        return $this->redirect(['index']);
    }


    protected function findModel($id)
    {
        if (($model = ApkList::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * 设置方案号
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionSetScheme($id)
    {
        $model = $this->findModel($id);
        $model->setScenario('set-scheme');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->success();
            return $this->redirect(['view', 'id' => $model->ID]);
        }

        if (!empty($model->scheme_id)) {
            $model->scheme_id = explode(',', $model->scheme_id);
        }

        return $this->render('set-scheme', [
            'model' => $model,
        ]);
    }

    public function actionLockerSwitch($id)
    {
        $model = $this->findModel($id);
        $form = new LockerSwitchForm();
        $form->app_name = $model->typeName;

        if ($form->load(Yii::$app->request->post()) && $total = $form->deal()) {
            $this->setFlash('success', "deal {$total} items");
            return $this->redirect(Url::to(['apk-list/index']));
        }

        return $this->render('locker-switch', [
            'model' => $form
        ]);
    }

}
