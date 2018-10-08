<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/4/10
 * Time: 14:34
 */

namespace backend\controllers;

use Yii;

class IndexController extends BaseController
{
    public function actionFrame()
    {
        $this->layout = false;
        return $this->render('frame');
    }

    public function actionIndex()
    {
        $data = $this->getServiceData();

        return $this->render('index', [
            'data' => $data
        ]);
    }

    /**
     * 设定语言
     */
    public function actionLanguage($lang)
    {
        $cookies = Yii::$app->response->cookies;

        $cookies->add(new \yii\web\Cookie([
            'name' => 'language',
            'value' => $lang,
            'expire' => time() + 24640000
        ]));

        $this->setFlash('success', 'Switch language successfully');
        $this->goBack(Yii::$app->request->headers['Referer']);
    }

    public function actionCheck()
    {

    }

    public function getServiceData()
    {
        $data = [
            'apiService' => ['key' => 'start.php','running' => false],
            'logService' => ['key' => 'log/analyse','running' => false],
            'queueService' => ['key' => 'queue/listen','running' => false],
        ];

        foreach ($data as $service => $value) {
            // 日志服务
            $fp = popen("ps -aux | grep {$value['key']} | grep -v grep", "r");
            $result = "";
            while (!feof($fp)) {
                $result .= fread($fp, 1024);
            }
            pclose($fp);
            if (strlen($result) > 1) {
                $data[$service]['running'] = true;
            }
        }

        return  $data;
    }

}