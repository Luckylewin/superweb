<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/4/10
 * Time: 14:34
 */

namespace backend\controllers;

use backend\models\Mac;
use common\components\Func;
use Yii;

class IndexController extends BaseController
{
    public function actionFrame()
    {
        $this->layout = 'frame';
        return $this->render('frame');
    }

    public function actionIndex()
    {
        $services  = $this->getServiceData();
        $onlineNum = Mac::getOnlineNum(3);

        return $this->render('index', [
            'data' => $services,
            'online' => $onlineNum
        ]);
    }

    /**
     * 设定语言
     * @param $lang
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
        //To Do 系统检查
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

    public function actionGo()
    {
        return $this->goBack(Func::getLastPage());
    }

}