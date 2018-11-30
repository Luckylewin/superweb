<?php

namespace backend\controllers;

use Yii;
use common\models\Order;
use common\models\search\OrderSearch;
use yii\web\NotFoundHttpException;


class OrderController extends BaseController
{
    public function actionIndex()
    {
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionStatics()
    {
        $todayNum = Order::getTotal(strtotime('today'), strtotime('tomorrow'));
        $todaySum = Order::getSumMoney(strtotime('today'), strtotime('tomorrow'));
        $totalNum = Order::getTotal(0, strtotime('tomorrow'));
        $totalSum = Order::getSumMoney(0, strtotime('tomorrow'));

        return $this->render('statics', [
            'todayNum' => $todayNum,
            'todaySum' => $todaySum,
            'totalNum' => $totalNum,
            'totalSum' => $totalSum
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
        $model = new Order();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->order_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->order_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->del_flag = Order::SOFT_DEL;
        $model->save(false);

        $this->success('success');
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
