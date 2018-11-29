<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/11/28
 * Time: 14:35
 */

namespace backend\controllers;

use backend\models\MultiLang;
use common\models\VodList;


class MultiLangController extends BaseController
{
    public function actionIndex($id)
    {
       \Yii::$app->assetManager->bundles = [
           'yii\web\JqueryAsset' => [
               'js'=>[]
           ],
           'yii\web\YiiAsset' => [
               'js' => []
           ],
           'yii\bootstrap\BootstrapPluginAsset' => [
               'js'=>[]
           ],
           'yii\bootstrap\BootstrapAsset' => [
               'css' => [],
           ],
       ];

        $tableName = \Yii::$app->request->get('table');
        $field     = \Yii::$app->request->get('field');

        if ($this->getRequest()->isPost) {

            $form = $this->getRequest()->post();
            if (!empty($form['name'])) {

                foreach ($form['name'] as $language => $value) {

                    if (isset($form['id']) && !empty($form['id'])) {
                        $lang = MultiLang::findOne(['id' => $form['id'][$language]]);
                        if ($lang->value != $value) {
                            $lang->value = $value;
                            $lang->save(false);
                        }
                    } else {
                        // 查询一下是否存在
                        $lang = MultiLang::find()->where(['table' => $tableName,'field' => $field, 'language' => $language, 'value' => $value])->one();
                        if (is_null($lang)) {
                            $multiLang = new MultiLang();
                            $multiLang->field = $field;
                            $multiLang->fid   = $id;
                            $multiLang->language = $language;
                            $multiLang->value = $value;
                            $multiLang->table = $tableName;
                            $multiLang->save(false);
                        }

                    }
                }
            }

            $this->success();
            return $this->redirect($this->getReferer());
        }

        $supported_languages = VodList::getLanguages();
        $multiLanguages      = MultiLang::find()->where(['fid' => $id, 'table' => $tableName, 'field' => $field])->asArray()->all();

        if (!$supported_languages) {
            $this->setFlash('error', '请设置多语言配置后再进行操作');

            return $this->redirect($this->getReferer());
        }

        $data = MultiLang::loadData($supported_languages, $multiLanguages);

        return $this->renderAjax('multi-language', [
            'data'      => $data,
            'fid'       => $id,
            'name'      => 'name'
        ]);
    }
}