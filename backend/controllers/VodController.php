<?php

namespace backend\controllers;

use backend\blocks\VodBlock;
use backend\models\form\BindVodSchemeForm;
use backend\models\Scheme;
use backend\models\VodToScheme;
use common\models\VodList;
use Yii;
use common\models\Vod;
use common\models\search\VodSearch;
use yii\web\NotFoundHttpException;
use yii\web\Response;


/**
 * VodController implements the CRUD actions for Vod model.
 */
class VodController extends BaseController
{

    /**
     * Lists all Vod models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VodSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (Yii::$app->request->getHeaders()->has('X-PJAX')) {
            return $this->renderAjax('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        } else {
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }

    }

    /**
     * Displays a single Vod model.
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
     * Creates a new Vod model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new VodBlock();
        if ($cid = Yii::$app->request->get('vod_cid')) {
            $model->vod_cid = $cid;
            $vodList = VodList::findOne($cid);
            $model->vod_trysee = $vodList->list_trysee;
            $model->vod_price = $vodList->list_price;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->vod_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Vod model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setFlash('info', Yii::t('backend', 'Success'));
            return $this->redirect(['view', 'id' => $model->vod_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionPushHome($id,$action)
    {
        $model = $this->findModel($id);
        $model->vod_home = $action;
        $model->save(false);

        $this->setFlash('info', Yii::t('backend', 'Success'));
        return $this->redirect(Yii::$app->request->referrer);
    }

   
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        $this->setFlash('success', Yii::t('backend', 'Success'));

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     *
     */
    public function actionBatchDelete()
    {
        $id = Yii::$app->request->get('id');
        $id = explode(',', $id);
        $data = Vod::find()->andFilterWhere(['in', 'vod_id', $id])->all();
        if ($data) {
            foreach ($data as $vod) {
                ($vod instanceof Vod ) && $vod->delete();
            }
        }

        $this->setFlash('info', Yii::t('backend', 'Success'));
        return $this->redirect(['index']);
    }

    /**
     * Finds the Vod model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Vod the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = VodBlock::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionSortAll($vod_cid)
    {
        VodBlock::sortAll($vod_cid);
        $this->success();

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionSort($id, $vod_cid, $action, $compare_id)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        if (in_array($action, ['up', 'down'])) {
           $sort = VodBlock::sortUpDown($action, $vod_cid, $id, $compare_id);
        } else {
            $sort = Yii::$app->request->get('sort');
            VodBlock::setSort($id, $sort);
        }

        return ['status' => 'success', 'data' => [
            'sort' => $sort
        ]];
    }

    public function actionBindScheme($id)
    {
        $model = new BindVodSchemeForm();
        $model->vod_id = $id;

        if (Yii::$app->request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return [
                    'status' => true,
                ];
            }

            return ['status' => false];

        } else {
            // 查询没有绑定的方案号
            $scheme_id = VodToScheme::findByVodId($id);
            $supportedSchemeID = Scheme::getSupportedSchemeByNotIn($scheme_id);
            $schemeOptions = Scheme::getOptions();

            $model->scheme_id = $supportedSchemeID;

            return $this->renderAjax('bind-scheme', [
                'model' => $model,
                'schemeOptions' => $schemeOptions
            ]);
        }

    }
}
