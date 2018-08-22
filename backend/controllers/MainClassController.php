<?php

namespace backend\controllers;

use backend\components\MyRedis;
use common\components\Func;
use http\Exception\InvalidArgumentException;
use Yii;
use common\models\MainClass;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * MainClassController implements the CRUD actions for MainClass model.
 */
class MainClassController extends BaseController
{
    /**
     * Lists all MainClass models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => MainClass::find(),
            'sort' => [
                'defaultOrder' => [
                    'use_flag' => SORT_DESC,
                    'sort' => SORT_ASC
                ]
            ]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MainClass model.
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
     * Creates a new MainClass model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MainClass();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing MainClass model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $field = Yii::$app->request->get('field');
            if ($field == 'sort') {
                  $model->sort = Yii::$app->request->post('sort');
                  $model->save(false);
            }
            return [
                'status' => 0
            ];
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setFlash('success', Yii::t('backend', 'Success'));
            return $this->redirect(['index', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing MainClass model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the MainClass model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MainClass the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MainClass::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionListCache($id)
    {
        $model = $this->findModel($id);
        $redis = MyRedis::init(MyRedis::REDIS_PROTOCOL);
        $cacheKeys = $redis->keys("OTT_LIST_XML_{$model->name}*");
        $data = [];

        if (!empty($cacheKeys)) {
            foreach ($cacheKeys as $key => $redisKey) {
                if (strpos($redisKey, 'VERSION') == false) {
                    $data[] = ['id' => $key, 'key_name'=>$redisKey];
                }
            }
        }

        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'models' => $data,
            'pagination' => false,
            'sort' => [
                'attributes' => ['id', 'key_name']
            ]
        ]);

        return $this->render('list-cache', [
            'dataProvider' => $dataProvider,
        ]);

    }

    public function actionViewCache($key)
    {
        $redis = MyRedis::init(MyRedis::REDIS_PROTOCOL);
        $cache = $redis->get($key);
        header("Content-type: application/xml");
        exit($cache);
    }


    public function actionExport($id)
    {
        if (empty($id)) {
            $this->setFlash('error', '请选择要导出的分类数据');
            return $this->redirect(Yii::$app->request->referrer);
        }

        $id = explode(',', trim($id));
        $result = MainClass::find()->where(['in', 'a.id', $id])->alias('a')
                                   ->with([
                                       'sub' => function($query) {
                                           return $query->with([
                                               'ownChannel' => function($query) {
                                                   return $query->with('ownLink');
                                               }
                                           ]);
                                       }
                                   ])

                                   ->asArray()->all();

        $str = '';

        foreach ($result as $value) {
            $mainClass = $value['name'];
            if (!empty($value['sub'])) {
                foreach ($value['sub'] as $val) {
                    $subClassName = $val['name'];
                    if (!empty($val['ownChannel'])) {
                         foreach ($val['ownChannel'] as $channel) {
                             $channelName = $channel['name'];
                             $channelIcon = empty($channel['image']) ? 'null' : $channel['image'];
                             if (!empty($channel['ownLink'])) {
                                 foreach ($channel['ownLink'] as $link) {
                                     $url = $link['link'];
                                     $method = $link['method'];
                                     $scheme = $link['scheme_id'];
                                     $use_flag = $link['use_flag'];
                                     $decode = $link['decode'];

                                     $str .= "{$mainClass},{$subClassName},{$channelName},{$channelIcon},{$url},{$method},{$scheme},{$use_flag},{$decode}" . PHP_EOL;
                                 }
                             }
                         }
                    }
                }
            }
        }

        if (!empty($str)) {
            Func::setDownloadHeader("OTT列表导出文件_" . date('YmdHi') . '.txt');
            return $str;
        }

        $this->setFlash('error', '没有数据可导出');
        return $this->redirect(Yii::$app->request->referrer);

    }

    /**
     * 导出频道的图标
     */
    public function actionExportImage($main_class_id)
    {
        ini_set('max_execution_time','1800');

        $main_class_id = explode(',', $main_class_id);
        if (empty($main_class_id)) {
            throw new BadRequestHttpException('错误的请求');
        }

        $data = [];
        foreach ($main_class_id as $id) {
           $data[] = MainClass::find()->where(['in', 'a.id', $id])
                                    ->alias('a')
                                    ->with([
                                        'sub' => function($query) {
                                            return $query->with('ownChannel');
                                        }])
                                    ->asArray()
                                    ->all();
        }

        $images = [];
        if (!empty($data)) {
            foreach ($data as $value) {
                foreach ($value as $val) {
                    foreach ($val['sub'] as $sub) {
                        foreach ($sub['ownChannel'] as $channel) {

                            if ($channel['image']) $images[] = ['name' => $channel['name'] , 'path' => $channel['image']];
                        }
                    }
                }
            }
        }

        $zipFile = tempnam('/tmp/', '');
        $savePath = '/tmp/export';

        !is_dir($savePath) && FileHelper::createDirectory($savePath);

        // 下载远程文件
        array_walk($images, function(&$v, $k) use ($savePath) {

            if ($k !=0 && $k % 5 == 0) sleep(1);

            $downloadPath = $savePath . '/' . $v['name'] . strstr(basename($v['path']), '.', false);

            $v['path'] = Func::getAccessUrl($v['path'], 3600);

            if (file_exists($downloadPath) == false) {
                file_put_contents($downloadPath, file_get_contents($v['path']));
            }
            $v['path'] = $downloadPath;
        });

        $zip = new \ZipArchive();
        $zip->open($zipFile,\ZipArchive::CREATE);   //打开压缩包

        foreach($images as $file){
            $zip->addFile($file['path'], basename($file['path']));   //向压缩包中添加文件
        }

        $zip->close();  //关闭压缩包

        FileHelper::removeDirectory($savePath); // 删除下载的文件

        Func::setDownloadZipHeader($zipFile, '频道图片导出.zip');
    }

}
