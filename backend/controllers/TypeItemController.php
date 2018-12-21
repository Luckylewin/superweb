<?php

namespace backend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use backend\models\form\RenameForm;
use backend\models\IptvType;
use backend\models\IptvTypeItem;


class TypeItemController extends BaseController
{
    public function actionIndex()
    {
        $type_id = Yii::$app->request->get('type_id');

        if ($type_id == false) {
            return $this->redirect(['vod-list/index']);
        }

        $this->session()->set('type_id', $type_id);

        $dataProvider = new ActiveDataProvider([
            'query' => IptvTypeItem::find()->where(['type_id' => $type_id]),
            'pagination' => ['pagesize' => 100],
            'sort' => [
                'defaultOrder' => [
                    'sort' => SORT_ASC,
                    'exist_num' => SORT_DESC,
                ]
            ]
        ]);

        $type = IptvType::findOne($type_id);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'type' => $type
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
        $model = new IptvTypeItem();
        if ($this->getRequest()->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $this->success();

                return $this->redirect(Url::to(['type-item/update', 'id' => $model->id]));
            }

            $this->error();
        }

        $model->type_id = Yii::$app->request->get('type_id');
        $model->sort    = 0;
        $model->is_show = 1;

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->getRequest()->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $field = Yii::$app->request->post('field');
            $value = Yii::$app->request->post('value');
            $model->$field = $value;
            $model->save(false);

            return ['status' => 'success'];
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->jump($model, 'info');
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        $this->success('info');
        return $this->redirect(['index', 'type_id' => $this->session()->get('type_id')]);
    }


    protected function findModel($id)
    {
        if (($model = IptvTypeItem::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    protected function jump($model, $status)
    {
        if ($this->session()->has('type_id')) {
            $this->setFlash($status, Yii::t('backend', 'Success'));
            return $this->redirect(['index', 'type_id' => $this->session()->get('type_id')]);
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

    public function actionRename($id)
    {
        $item = $this->findModel($id);
        $model = new RenameForm();

        if ($this->getRequest()->isPost) {
            $model->load($this->getRequest()->post());
            if ($model->rename() !== true) {
                $this->setFlash('error', $model->getFirstErrors()[0]);
            }

            $this->success();
            return $this->redirect($this->getReferer());
        }

        $model->oldName = $item->name;
        $model->id = $item->id;

        return $this->renderAjax('rename', [
            'model' => $model
        ]);
    }
}
