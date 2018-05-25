<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 2018/3/9
 * Time: 9:06
 */

namespace common\grid;

use Yii;
use yii\helpers\Html;

class MyActionColumn extends \yii\grid\ActionColumn
{
    public $template = "{view}&nbsp;{update}&nbsp;{delete}";
    public $size = 'btn-xs';
    /**
     * Initializes the default button rendering callbacks.
     */
    protected function initDefaultButtons()
    {
        $this->initDefaultButton('view', null, ['class'=>'btn btn-info ' . $this->size ]);
        $this->initDefaultButton('update', null , ['class'=>'btn btn-primary '. $this->size]);
        $this->initDefaultButton('delete', null,[
            'class'=>'btn btn-danger ' . $this->size ,
            'data-confirm' => Yii::t('yii', '您确定要删除此项吗？?'),
            'data-method' => 'post',
        ]);
    }

    /**
     * @param string $name
     * @param string $iconName
     * @param array $additionalOptions
     */
    protected function initDefaultButton($name, $iconName,  $additionalOptions = [])
    {
        if (!isset($this->buttons[$name]) && strpos($this->template, '{' . $name . '}') !== false) {
            $this->buttons[$name] = function ($url, $model, $key) use ($name, $additionalOptions) {
                switch ($name) {
                    case 'view':
                        $title = Yii::t('yii', '查看');
                        break;
                    case 'update':
                        $title = Yii::t('yii', '编辑');
                        break;
                    case 'delete':
                        $title = Yii::t('yii', '删除');
                        break;
                    default:
                        $title = ucfirst($name);
                }

                $options = array_merge([
                    'title' => $title,
                    'aria-label' => $title,
                    'data-pjax' => '0',
                ], $additionalOptions, $this->buttonOptions);


                return Html::a($title, $url, $options);
            };
        }
    }

}