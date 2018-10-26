<?php

namespace backend\controllers;

use common\components\Func;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use backend\models\Mac;
use backend\models\search\MacSearch;
use backend\models\form\ImportMacForm;
use backend\models\SysClient;
use console\queues\SyncJob;

/**
 * MacController implements the CRUD actions for Mac model.
 */
class MacController extends BaseController
{
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


    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    public function actionCreate()
    {
        $model = new Mac();
        $model->setScenario('create');

        if ($this->getRequest()->isAjax) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $this->setFlash('success', Yii::t('backend', 'Success'));
                return [
                    'status' => 0,
                    'msg' => Yii::t('backend', 'Success')
                ];
            }
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->setScenario('update');

        $this->rememberReferer();
        if (preg_match('/\d+\s+[year|day|month]/', $model->contract_time) == false) {
            $model->contract_time = '1 month';
            $model->save(false);
        }

        list($model->contract_time, $model->unit) = explode(' ', $model->contract_time);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setFlash('info', Yii::t('backend', 'Success'));
            return $this->redirect(Func::getLastPage());
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    public function actionDelete($id)
    {
        // 删除关联数据
        $model = $this->findModel($id);

        try {
            $model->delete();
            $this->setFlash('success', Yii::t('backend', 'Success'));
        } catch (\Exception $e) {
            $this->setFlash('success', Yii::t('backend', 'operation failed'));
        }

        return $this->redirect(['index']);
    }

    public function actionBatchDelete()
    {
        $this->enableCsrfValidation = false;
        if (Yii::$app->request->isAjax) {
            $macs = Yii::$app->request->post('macs');
            $macs = explode(',', $macs);
            if (!empty($macs)) {
                array_walk($macs, function($v, $k) {
                    Mac::findOne(['MAC' => $v])->delete();
                });

                Yii::$app->session->setFlash('success', Yii::t('backend', 'Success'));
                Yii::$app->response->format = Response::FORMAT_JSON;
            }
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

    protected function findModel($id)
    {
        if (($model = Mac::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Ajax校验
     * @param string $scenario
     * @return array
     */
    public function actionValidateForm($scenario = 'update')
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new Mac();
        $model->setScenario($scenario);

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

    public function actionSyncOnline()
    {
        Yii::$app->queue->push(new SyncJob());
        $this->setFlash('info', 'Success');
        return $this->redirect($this->getReferer());
    }
}
