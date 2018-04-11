<?php

namespace backend\controllers;


use backend\models\search\ApkListSearch;
use Yii;
use backend\models\ApkList;
use backend\models\search\Apk;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;


/**
 * ApkListController implements the CRUD actions for ApkList model.
 */
class ApkListController extends BaseController
{

   /* public function actions()
    {
        return ArrayHelper::merge(parent::actions(), [
            'editsort' => [                                       // identifier for your editable column action
                'class' => EditableColumnAction::className(),     // 动作类
                'modelClass' => ApkList::className(),   // 要被编辑的模型
                'outputValue' => function ($model, $attribute, $key, $index) {
                    return (int) $model->$attribute; //返回任意你想要的内容
                },
                'outputMessage' => function($model, $attribute, $key, $index) {
                    return '';                                  // any custom error to return after model save
                },
                'showModelErrors' => true,                        // show model validation errors after save
                'errorOptions' => ['header' => ''],           // error summary HTML options
                'postOnly' => true,
                // 'ajaxOnly' => true,
                // 'findModel' => function($id, $action) {},
                // 'checkAccess' => function($action, $model) {}
            ]
        ]);
    }*/

    /**
     * Lists all ApkList models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ApkListSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ApkList model.
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
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ApkList();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->ID]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->ID]);
        }
        if (!empty($model->scheme_id)) {
            $model->scheme_id = explode(',', $model->scheme_id);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', '操作成功');
        return $this->redirect(['index']);
    }

    /**
     * @param integer $id
     * @return ApkList the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ApkList::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
