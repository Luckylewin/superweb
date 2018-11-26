<?php

namespace backend\controllers;

use backend\models\form\RenameForm;
use backend\models\IptvType;
use backend\models\MultiLang;
use common\models\Type;
use common\models\VodList;
use Yii;
use backend\models\IptvTypeItem;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\Response;

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
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return ['status' => 'success'];
            }

            return ['status' => 'fail'];
        }

        $model->type_id = Yii::$app->request->get('type_id');
        $model->sort    = 0;
        $model->is_show = 1;

        return $this->renderAjax('create', [
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
    
    public function actionMultiLanguage($id)
    {
        if ($this->getRequest()->isPost) {
            $form = $this->getRequest()->post();
            if (!empty($form['name'])) {
                foreach ($form['name'] as $language => $value) {
                    $multiLang = new MultiLang();
                    $multiLang->field = 'name';
                    $multiLang->fid   = $id;
                    $multiLang->language = $language;
                    $multiLang->value = $value;
                    $multiLang->save(false);
                }
            }

            $this->success();
            return $this->redirect($this->getReferer());
        }

        $item = IptvTypeItem::find()
                            ->with('type')
                            ->with('multiLanguage')
                            ->where(['id' => $id])
                            ->asArray()
                            ->one();

        $list = VodList::find()->select(['supported_language', 'list_id'])->where(['>','list_id', 0])->one();

        if (!$languages = $list->supported_language) {
            $this->setFlash('error', '请设置多语言配置后再进行操作');

            return $this->redirect(['iptv-type/set-language', 'id' => $list->list_id]);
        }

        $languages = json_decode($languages, true);
        $data = array_flip($languages);
        array_walk($data, function(&$v) {$v="";});
        if (!empty($item['multiLanguage'])) {
            foreach ($languages as $language) {
                foreach ($item['multiLanguage'] as $lang) {
                    if ($lang['language'] == $language) {
                        $data[$language] = $lang['value'];
                    }
                }
            }
        }

        return $this->renderAjax('multi-language', [
            'data'      => $data,
            'fid'       => $id,
            'name'      => 'name'
        ]);
    }
    
}
