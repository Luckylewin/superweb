<?php

namespace backend\controllers;

use backend\models\PlayGroup;
use common\components\Func;
use common\models\Vod;
use http\Url;
use Yii;
use common\models\Vodlink;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * LinkController implements the CRUD actions for Vodlink model.
 */
class LinkController extends BaseController
{

    public function actionIndex()
    {
        $group_id = Yii::$app->request->get('group_id', false);
        $dataProvider = new ActiveDataProvider([
            'query' => Vodlink::find()->where(['group_id' => $group_id]),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }


    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id)
        ]);
    }


    public function actionCreate()
    {
        $model = new Vodlink();
        $model->group_id = Yii::$app->request->get('group_id', false);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $playGroup = PlayGroup::findOne(['id' => $model->group_id]);
            $this->success();
            return $this->redirect(['play-group/index', 'vod_id' => $playGroup->vod_id]);
        }

        $model->episode = $model->getNextEpisode($model->group_id);
        $model->save_type = Vodlink::FILE_LINK;
        $model->hasErrors() && Yii::$app->session->setFlash('error', $model->getErrorSummary(true));

        return $this->render('create', [
            'model' => $model
        ]);
    }


    public function actionUpdate($id)
    {
        $this->rememberReferer();
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setFlash('info', Yii::t('backend', 'Success'));
            return $this->redirect(Func::getLastPage());
        }

        return $this->render('update', [
            'model' => $model
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect($this->getReferer());
    }


    protected function findModel($id)
    {
        if (($model = Vodlink::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionBatchDelete()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $id = Yii::$app->request->post('id');
        Vodlink::deleteAll(['in', 'id', $id]);
        $this->setFlash('info', '删除成功');
        return [
            'status'=>0
        ];
    }
}
