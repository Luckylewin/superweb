<?php

namespace backend\controllers;

use \Yii;
use backend\models\Menu;
use backend\models\search\MenuSearch;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use common\libs\Tree;
use yii\web\Response;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class MenuController extends BaseController
{

    public function beforeAction($action)
    {
        if ($action->id == 'auth') {
            return parent::beforeAction($action);
        }
        // 检查是否有修改权限
        if ( Yii::$app->session->has('auth') == false || Yii::$app->session['auth']['expire_time'] < time()) {
            $this->setFlash('error', Yii::t('backend', 'Need to enter the opcode'));
            return $this->redirect(['menu/auth']);
        }
        return true;
    }

    public function actionAuth()
    {
        if (Yii::$app->request->isPost) {
            $password = Yii::$app->request->post('password');
            $enrypted = md5(md5(md5($password).'iptv'));

            if ($enrypted == 'bc12fa5b36ff49cf4104433d32a3eaec') {
                $session = Yii::$app->session;
                $data = ['expire_time' => time() + 1200]; //这里设置10秒过期
                $session['auth'] = $data;
                $this->setFlash('success', Yii::t('backend', 'Successful certification'));
                return $this->redirect(['menu/index']);
            } else {
                $this->setFlash('error', Yii::t('backend', 'Authentication failed'));
            }
        }

        return $this->render('auth');
    }

    /**
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->request->isPost) {
            $sorts = Yii::$app->request->post('sort');
            if (!empty($sorts)) {
                foreach ($sorts as $id => $v) {
                    $model = Menu::findOne($id);
                    $model->sort = $v;
                    $model->save();
                }
                Yii::$app->session->setFlash('success', Yii::t('backend', 'Success'));
            }
        }
        $searchModel = new MenuSearch();
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
        $model = new Menu();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('info', Yii::t('backend', 'Success'));
            return $this->redirect(['index']);
        } else {
            $model->pid = Yii::$app->request->get('pid', 0);
            $arr = Menu::find()->asArray()->all();
            $treeObj = new Tree($arr);
            return $this->render('create', [
                'model' => $model,
                'treeArr' => $treeObj->getTree(),
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $this->enableCsrfValidation = false;

        $model = $this->findModel($id);

        if (Yii::$app->request->isAjax) {
            $item = $this->findModel($id);
            $item->display = !$item->display;
            $item->save(false);
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'status' => 'success'
            ];
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('info', Yii::t('backend', 'Success'));
            return $this->redirect(['index']);
        } else {
            $arr = Menu::find()->asArray()->all();
            $treeObj = new Tree($arr);
            return $this->render('update', [
                'model' => $model,
                'treeArr' => $treeObj->getTree(),
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
        if (($model = Menu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
