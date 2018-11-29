<?php

namespace backend\controllers;

use backend\models\form\BatchCreateCardForm;
use Yii;
use backend\models\RenewalCard;
use backend\models\search\RenewalCardSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 *  充值卡
 */
class RenewalCardController extends BaseController
{

    /**
     * Lists all RenewalCard models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RenewalCardSearch();
        $dataProvider = $searchModel->index(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionBatch()
    {
        $searchModel = new RenewalCardSearch();
        $dataProvider = $searchModel->batch(Yii::$app->request->queryParams);

        return $this->render('batch', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionQuery()
    {
        $searchModel = new RenewalCardSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('batch', [
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


    public function actionCreate()
    {
        $model = new RenewalCard();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->card_num]);
        }

        return $this->render('create', [
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
        if (($model = RenewalCard::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionBatchCreate()
    {
        $model = new BatchCreateCardForm();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->success();
            return $this->redirect(['renewal-card/index']);
        }

        return $this->render('batch-create', [
            'model' => $model
        ]);
    }

    public function actionBatchDelete($batch_id)
    {
        RenewalCard::deleteAll(['batch' => $batch_id]);
        $this->success();

        return $this->redirect($this->getReferer());
    }
}
