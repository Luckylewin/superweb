<?php

namespace backend\controllers;

use backend\models\Admin;
use backend\models\Crontab;
use Yii;
use backend\models\SysClient;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * ClientController implements the CRUD actions for SysClient model.
 */
class ClientController extends BaseController
{

    /**
     * Lists all SysClient models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => SysClient::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SysClient model.
     * @param integer $id
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
     * Creates a new SysClient model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SysClient();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SysClient model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SysClient model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the SysClient model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SysClient the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SysClient::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionBindAccount()
    {
        $id = Yii::$app->request->get('id');

        if (Yii::$app->request->isPost) {
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $this->setFlash('info', Yii::t('backend', 'Success'));
                return $this->redirect(Yii::$app->request->referrer);
            }
        }

        $occupy_id = ArrayHelper::getColumn(SysClient::find()->select('admin_id')->all(), 'admin_id');
        $admin = Admin::find()->select('id,username')->where(['not in', 'id', $occupy_id])->all();

        if (empty($admin)) {
            return "<div class='col-md-12 center'><h3>请添加新的管理员!</h3>" .
                    Html::a('添加管理员', Url::to(['admin/create']),['class' => 'btn btn-info btn-sm']) . "</div>";
        }

        $admin = ArrayHelper::map($admin, 'id', 'username');
        $model = $this->findModel($id);

        return $this->renderAjax('_bind_form', [
            'model' => $model,
            'admin' => $admin
        ]);
    }

    public function actionAnnaIptv()
    {
        // 查找点播
        $task = Crontab::find()->where(['route' => 'client/anna-iptv'])->one();

        if (is_null($task)) {
            $this->setFlash('error', '请添加该任务到定时任务');
            return $this->redirect(Yii::$app->request->referrer);
        }

        if ($task->status == Crontab::RUNNING || $task->status == Crontab::READY) {
            $this->setFlash('info', '任务正在运行, 更新数据需要时间');
            return $this->redirect(Yii::$app->request->referrer);
        }

        $task->last_rundate = date('Y-m-d H:i:s');
        $task->next_rundate = date('Y-m-d H:i:s', time() + 10);
        $task->status = Crontab::READY;
        $task->save(false);

        $this->setFlash('success', '开始更新数据');
        return $this->redirect(Yii::$app->request->referrer);
    }

}
