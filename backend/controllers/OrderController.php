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
        $month = Yii::$app->request->post('month', date('Y-m'));
        $monStart = strtotime('first day of this month', strtotime($month . '-01'));
        $monEnd = strtotime('last day of this month', strtotime($month . '-01'));

        $prev = date('Y-m', strtotime('-1 month', strtotime($month . '-01')));
        $next = date('Y-m', strtotime('+1 month', strtotime($month . '-01')));


        $todayNum = Order::getTotal(strtotime('today'), strtotime('tomorrow'));
        $todaySum = Order::getSumMoney(strtotime('today'), strtotime('tomorrow'));
        $monthNum = Order::getTotal($monStart, $monEnd);
        $monthSum = Order::getSumMoney($monStart, $monEnd);
        $totalNum = Order::getTotal(0, strtotime('tomorrow'));
        $totalSum = Order::getSumMoney(0, strtotime('tomorrow'));

        // 该月按天数查看
        $monthDetail = Order::getMonthDataSpiltByDate($monStart, $monEnd);

        return $this->render('statics', [
             'month' => $month,
            'todayNum' => $todayNum,
            'todaySum' => $todaySum,
            'monthNum' => $monthNum,
            'monthSum' => $monthSum,
            'totalNum' => $totalNum,
            'totalSum' => $totalSum,
            'prev' => $prev,
            'next' => $next,
            'monthDetail' => $monthDetail
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
