<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/11/22
 * Time: 9:50
 */

namespace common\widgets\switchInput;


use yii\base\InvalidArgumentException;
use yii\bootstrap\Widget;

class SwitcherInputWidget extends Widget
{
    /**
     * @var integer $id 要进行状态改变的id
     */
    public $id;
    /**
     * @var string $idField id字段
     */
    public $idField = 'id';

    public $field = 'display';

    /**
     * @var string $url 处理的状态改变的地址
     */
    public $url;
    /**
     * @var string $method 请求方法
     */
    public $method = 'POST';

    /**
     * @var string
     */
    public $successTips = 'success';
    /**
     * @var string
     */
    public $errorTips = 'error';

    public $csrfToken;

    /**
     * @var int
     */
    public $defaultCheckedStatus = false;

    public function init()
    {
        if (empty($this->url)) {
            throw new InvalidArgumentException('url必须设置,否则无法处理状态');
        }

        if (empty($this->field)) {
            throw new InvalidArgumentException('field必须设置,否则无法处理状态');
        }

        if (empty($this->id)) {
            throw new InvalidArgumentException('id必须设置，否则无法处理状态');
        }

        if (empty($this->csrfToken)) {
            $this->csrfToken = \Yii::$app->request->csrfToken;
        }
    }

    public function run()
    {
        return $this->render('index',[
            'id'      => $this->id,
            'url'     => $this->url,
            'method'  => $this->method,
            'idField' => $this->idField,
            'field'   => $this->field,
            'success' => $this->successTips,
            'error'   => $this->errorTips,
            'checked' => $this->defaultCheckedStatus ? 'checked' : false,
            'csrf'    => $this->csrfToken
        ]);
    }
}