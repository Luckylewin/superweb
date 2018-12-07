<?php
namespace backend\controllers;

use Yii;
use backend\models\form\config\ConfigForm;
use backend\models\form\config\EmailForm;
use backend\models\form\config\OtherForm;
use backend\models\form\config\BasicForm;
use backend\models\form\OttSettingForm;
use backend\models\Config;

/**
 * ConfigController implements the CRUD actions for Config model.
 */
class ConfigController extends BaseController
{

    protected $formParams = [];

    public function actionBasic()
    {
        $formModel = new BasicForm();
        $model = Config::findOne(['keyid' => Config::TYPE_BASIC]);

        if(Yii::$app->request->isPost) {
            $this->saveConfig($model, $formModel, Config::TYPE_BASIC);
            return $this->redirect($this->getReferer());
        }

        $formModel = $formModel->loadData($model->data?:[]);

        return $this->render('basic', [
            'model' => $formModel
        ]);
    }


    private function saveConfig($model,ConfigForm $formModel, $type)
    {
        if(empty($model)) {
            $model = new Config();
            $model->keyid = $type;
        }

        $data = $this->getRequest()->post($formModel->formName());

        $model->data = $data;
        $model->save(false);

        $formModel->loadData($data)->setData()->saveToConfigFile();
        $this->success();
    }

    public function actionOther()
    {
        $formModel = new OtherForm();
        $model = Config::findOne(['keyid' => Config::TYPE_OTHER]);

        if(Yii::$app->request->isPost) {
            $this->saveConfig($model, $formModel, Config::TYPE_OTHER);

            return $this->redirect($this->getReferer());
        }

        $formModel->loadData($model->data??[]);

        return $this->render('other', [
            'model' => $formModel
        ]);
    }

    public function actionSendMail()
    {
        $formModel = new EmailForm();
        $model = Config::findOne(['keyid' => Config::TYPE_EMAIL]);

        if(Yii::$app->request->isPost) {
            $this->saveConfig($model, $formModel, Config::TYPE_EMAIL);

            return $this->redirect($this->getReferer());
        }

        $formModel->loadData($model->data??[]);

        return $this->render('email', [
            'model' => $formModel
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

}
