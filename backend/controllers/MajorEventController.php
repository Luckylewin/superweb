<?php

namespace backend\controllers;

use Yii;
use backend\models\MajorEvent;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

/**
 * 主要赛事
 */
class MajorEventController extends BaseController
{

    /**
     * Lists all MajorEvent models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => MajorEvent::find()->where(['>', 'time', strtotime('today')]),
            'sort' => [
                'defaultOrder' => [
                    'time' => SORT_ASC,
                    'sort' => SORT_ASC
                ]
            ],
            'pagination' => [
                'pageSize' => 50
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
        $model = new MajorEvent();
        $post = Yii::$app->request->post();
        if ($model->load($post) && $model->initData($post) && $model->save()) {
            $this->setFlash('success', Yii::t('backend', 'Success'));
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    public function actionUpdate($id)
    {
        $this->rememberReferer();
        $model = $this->findModel($id);

        //初始化日期
        if (Yii::$app->request->isGet) {
            $model->beforeUpdate($model);
        } else if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($model->load($post) && $model->initData($post) && $model->save()) {
                $this->setFlash('success', Yii::t('backend', 'Success'));
                return $this->redirect($this->getLastPage());
            }

            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->render('update', [
            'model' => $model,
            'teams' => $model->teams
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        $this->setFlash('info', Yii::t('backend', 'Success'));
        return $this->redirect(Yii::$app->request->referrer);
    }


    protected function findModel($id)
    {
        if (($model = MajorEvent::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionBatchDelete()
    {
        $ids = Yii::$app->request->get('ids');

        if (!empty($ids)) {
            $ids = explode(',', $ids);
            MajorEvent::deleteAll(['in', 'id', $ids]);
            $this->setFlash('success', Yii::t('backend', 'Success'));
            return $this->redirect(Yii::$app->request->referrer);
        }
        $this->setFlash('error', Yii::t('backend', 'operation failed'));
        return $this->redirect(Yii::$app->request->referrer);
    }

}
