<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use backend\components\AccessControl;
use backend\models\Config;

class BaseController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'except' => ['error','login'],
                'rules' => [
                    [
                        //表示只允许认证过的用户执行 其roles用@表示 游客用?表示
                        'actions' => ['logout', 'frame', 'auth', 'reset-password', 'language'],
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
                'denyCallback'  => function ($rule, $action) {
                    Yii::$app->user->loginRequired();
                },
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete-all' => ['post']
                ],
            ],
        ];
    }


    /**
     * 初始化配置信息
     * 网站配置或模板配置等
     */
    public function init()
    {
        parent::init();

        Yii::$app->params['basic'] = Yii::$app->cache->getOrSet('basic', function () {
            return Config::getConfigs('basic');
        });

        return true;
    }

    public function setFlash($status, $message)
    {
        \Yii::$app->session->setFlash($status, $message);
    }

    public function success($status = 'success')
    {
        $this->setFlash($status, Yii::t('backend', 'Success'));
    }

    public function error($status = 'error')
    {
        $this->setFlash($status, Yii::t('backend', 'Error'));
    }

    /**
     * @return mixed|\yii\web\Session
     */
    public function session()
    {
        return Yii::$app->session;
    }

    public function getReferer()
    {
        return Yii::$app->request->referrer;
    }

}