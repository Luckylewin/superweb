<?php

namespace backend\controllers;



use backend\models\form\ImportMacForm;
use backend\models\SysClient;
use Yii;

use yii\bootstrap\ActiveForm;
use yii\web\Response;
use backend\models\Mac;
use backend\models\search\MacSearch;
use yii\web\NotFoundHttpException;



/**
 * MacController implements the CRUD actions for Mac model.
 */
class MacController extends BaseController
{

    /**
     * Lists all Mac models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MacSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if ($orderQuery = Yii::$app->request->get('MacSearch')) {
            $queryParams = base64_encode(json_encode(Yii::$app->request->queryParams));
        } else {
            $queryParams = '';
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'queryParams' => $queryParams
        ]);
    }

    /**
     * Displays a single Mac model.
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
     * Creates a new Mac model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Mac();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setFlash('success', Yii::t('backend', 'Success'));
            return $this->redirect(['mac/index']);
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Mac model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        list($model->contract_time, $model->unit) = explode(' ', $model->contract_time);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setFlash('info', Yii::t('backend', 'Success'));
            return $this->redirect(['mac/index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Mac model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        // 删除关联数据
        $model = $this->findModel($id);

        try {
            $detail = $model->detail;
            if ($detail) {
                $detail->delete();
            }
            $model->delete();
            Yii::$app->session->setFlash('success', Yii::t('backend', 'Success'));
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('success', Yii::t('backend', 'operation failed'));
        }

        return $this->redirect(['index']);
    }

    public function actionBatchDelete()
    {
        $this->enableCsrfValidation = false;
        if (Yii::$app->request->isAjax) {
            $macs = Yii::$app->request->post('macs');
            array_walk($macs, function($v, $k) {
               Mac::findOne(['MAC' => $v])->delete();
            });

            Yii::$app->session->setFlash('success', Yii::t('backend', 'Success'));
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['status' => 0];
        }
        return false;
    }

    public function actionExport()
    {
        $queryParams = Yii::$app->request->get('queryParams');

        if ($queryParams) {
            $queryParams = json_decode(base64_decode($queryParams), true);
            $data = (new MacSearch())->search($queryParams, true)->getModels();
        } else {
            $data = Mac::find()->all();
        }

        if (count($data) == 0) {
            Yii::$app->session->setFlash('error', Yii::t('backend', 'No data exported'));
            return $this->redirect(Yii::$app->request->referrer);
        }

        $string = Mac::exportCSV($data);
        exit($string);
    }


    /**
     * Finds the Mac model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Mac the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mac::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Ajax校验
     * @return array
     */
    public function actionValidateForm()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new Mac();
        $model->load(Yii::$app->request->post());

        return ActiveForm::validate($model);
    }

    public function actionImport()
    {
        $clients = SysClient::getAll();
        $model = new ImportMacForm();

        if ($model->load(Yii::$app->request->post()) && $total = $model->import()) {
            $this->setFlash('success', Yii::t('backend', 'Successfully imported') . $total . Yii::t('backend', 'items'));
            return $this->redirect(['mac/index']);
        }

        if ($model->hasErrors()) {
            print_r($model->getErrorSummary(true));
        }

        return $this->render('_import', [
            'model' => $model,
            'clients' => $clients
        ]);
    }
}
