<?php

namespace backend\controllers;

use backend\models\IptvType;
use Yii;
use backend\models\IptvTypeItem;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

/**
 * TypeItemController implements the CRUD actions for IptvTypeItem model.
 */
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
            'sort' => [
                'defaultOrder' => [
                    'sort' => SORT_ASC
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->jump($model, 'success');
        }

        $model->type_id = Yii::$app->request->get('type_id');

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

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

}
