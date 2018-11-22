<?php
namespace backend\controllers;

use backend\models\form\OttSettingForm;
use Yii;
use backend\models\Config;
use backend\models\search\ConfigSearch;
use yii\web\NotFoundHttpException;

/**
 * ConfigController implements the CRUD actions for Config model.
 */
class ConfigController extends BaseController
{

    public function actionBasic()
    {
        $model = Config::find()->where(['keyid' => 'basic'])->one();
        if(Yii::$app->request->isPost) {
            if(empty($model)) {
                $model = new Config();
                $model->keyid = 'basic';
            }

            $form = Yii::$app->request->post('form');
            $model->data = json_encode($form);
            $model->save();
            $this->setFlash('success', Yii::t('backend', 'Success'));
        }
        if(!isset($model->data)) $formParams = [];
        else $formParams = json_decode($model->data, true);
        if(!isset($formParams['close'])) $formParams['close'] = 0;
        if(!isset($formParams['close_reason'])) $formParams['close_reason'] = '站点升级中, 请稍后访问!';

        return $this->render('basic', [
            'formParams' => $formParams,
        ]);
    }

    public function actionSendMail()
    {
        $model = Config::find()->where(['keyid' => 'sendmail'])->one();
        if(Yii::$app->request->isPost) {
            if(empty($model)) {
                $model = new Config();
                $model->keyid = 'sendmail';
            }
            $form = Yii::$app->request->post('form');
            $model->data = json_encode($form);
            $model->save();
            $this->setFlash('success', Yii::t('backend', 'Success'));
        }
        if(!isset($model->data)) $formParams = [];
        else $formParams = json_decode($model->data, true);
        if(!isset($formParams['auth'])) $formParams['auth'] = 1;
        if(!isset($formParams['openssl'])) $formParams['openssl'] = 1;
        $supportSsl = 'disabled';
        if(function_exists('openssl_open')) $supportSsl = '';
        return $this->render('send-mail', [
            'formParams' => $formParams,
            'supportSsl' => $supportSsl,
        ]);
    }

    public function actionAttachment()
    {
        $model = Config::find()->where(['keyid' => 'attachment'])->one();
        if(Yii::$app->request->isPost) {
            if(empty($model)) {
                $model = new Config();
                $model->keyid = 'attachment';
            }
            $form = Yii::$app->request->post('form');
            $model->data = json_encode($form);
            $this->setFlash('success', Yii::t('backend', 'Success'));
            $model->save();
        }
        if(!isset($model->data)) $formParams = [];
        else $formParams = json_decode($model->data, true);
        if(!isset($formParams['attachment_size'])) $formParams['attachment_size'] = 2048;
        if(!isset($formParams['attachment_suffix'])) $formParams['attachment_suffix'] = 'jpg|jpeg|gif|bmp|png';
        if(!isset($formParams['watermark_enable'])) $formParams['watermark_enable'] = 1;
        if(!isset($formParams['watermark_pos'])) $formParams['watermark_pos'] = 0;
        $supportSsl = 'disabled';
        if(function_exists('openssl_open')) $supportSsl = '';
        return $this->render('attachment', [
            'formParams' => $formParams,
            //'supportSsl' => $supportSsl,
        ]);
    }

    public function actionOttSetting()
    {
        $model = new OttSettingForm();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->success();
        }

        return $this->render('ottcharge', [
             'model' => $model,
        ]);

    }

    public function actionIndex()
    {
        $searchModel = new ConfigSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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
        $model = new Config();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Config::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
