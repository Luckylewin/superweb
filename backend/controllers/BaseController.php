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
                'except' => ['error','login'], //except是除了以外
                'rules' => [
                    //行为过滤器
                   /* [
                        //表示无条件通过
                        'actions' => ['login','error'],
                        'allow' => true,
                        'roles' => ['?','@'],
                    ],*/
                    [
                        //表示只允许认证过的用户执行 其roles用@表示 游客用?表示
                        'actions' => ['logout','frame'],
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ],
            'verbs' => [
                /**
                 * 过滤http请求的行为过滤器
                 */
                'class' => VerbFilter::className(),
                'actions' => [
                    //'logout' => ['post'],
                    //'delete' => ['get'],
                    'delete-all' => ['post']
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
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
        Yii::$app->params['basic'] = Config::getConfigs('basic');
        return true;
    }

    

}