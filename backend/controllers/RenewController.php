<?php

namespace backend\controllers;

use Yii;
use backend\components\MyRedis;
use backend\models\Mac;
use backend\models\RenewLog;
use backend\models\RechargeCard;
use backend\models\search\RechargeCardSearch;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * RenewController implements the CRUD actions for RechargeCard model.
 */
class RenewController extends BaseController
{
    /**
     * Lists all RechargeCard models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RechargeCardQuery();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RechargeCard model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new RechargeCard model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RechargeCard();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->card_num]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing RechargeCard model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->card_num]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing RechargeCard model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the RechargeCard model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return RechargeCard the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RechargeCard::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionRenew($mac)
    {

        if (Yii::$app->request->isPost) {
            $mac = Yii::$app->request->get('mac');
            $mac = Mac::findOne(['MAC' => $mac]);

            $error = false;
            if ($mac->use_flag === Mac::NOT_ACTIVE) {
                Yii::$app->session->setFlash('error', "该帐号未激活");
                $error = true;
            } else if (strtotime($mac->duetime) == strtotime('1970')) {
                Yii::$app->session->setFlash('error', "该帐号为无限期");
                $error = true;
            } else if (is_null($mac)) {
                Yii::$app->session->setFlash('error', "数据不存在");
                $error = true;
            } else if (Yii::$app->request->post('contract_time') <= 0) {
                Yii::$app->session->setFlash('error', "续费时间错误");
                $error = true;
            }

            if ($error) {
                return $this->redirect(Url::to(['renew/renew', 'mac' => $mac->MAC]));
            }

            $renewTime = Yii::$app->request->post('contract_time') . " ". Yii::$app->request->post('unit');



            //判断是否已经过期了
            if ($mac->use_flag == Mac::NORMAL) {
                $mac->duetime = date('Y-m-d H:i:s', strtotime("+ $renewTime",strtotime($mac->duetime)));

            } else if ($mac->use_flag == Mac::EXPIRED) {
                $mac->duetime = date('Y-m-d H:i:s', strtotime("+ $renewTime"));
                $mac->use_flag = Mac::NOT_ACTIVE;
            }

            $mac->contract_time = $renewTime;
            //更新数据库,缓存
            $mac->save(false);
            MyRedis::init(MyRedis::REDIS_DEVICE_STATUS);
            Yii::$app->session->setFlash('success', "续费操作成功");

            //记录日志
            $renewLog = new RenewLog();
            $renewLog->mac = $mac->MAC;
            $renewLog->renew_period = $renewTime;
            $renewLog->expire_time = $mac->duetime;
            $renewLog->card_num = '-';
            $renewLog->renew_operator = '0';
            $renewLog->save(false);

            return $this->redirect(Yii::$app->request->referrer);
        }

        $mac = Mac::findOne(['MAC' => $mac]);

        $renewRecord = ArrayHelper::toArray(RenewLog::find()->where(['mac' => $mac->MAC])->all());

        return $this->render('renew',[
            'model' => $mac,
            'renewRecord' => $renewRecord
        ]);
    }
}
