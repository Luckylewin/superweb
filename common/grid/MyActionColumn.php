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
        $this->initDefaultButton('update', null , [
            'class'=>'btn btn-primary frame-open '. $this->size
        ]);
        $this->initDefaultButton('delete', null,[
            'class'=>'btn btn-danger ' . $this->size ,
            'data-confirm' => Yii::t('yii', Yii::t('backend', 'Are you sure?')),
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
                        $title = Yii::t('backend', 'View');
                        break;
                    case 'update':
                        $title = Yii::t('backend', 'Edit');
                        break;
                    case 'delete':
                        $title = Yii::t('backend', 'Delete');
                        break;
                    default:
                        $title = ucfirst($name);
                }

                $options = array_merge([
                    'title' => $title,
                    'aria-label' => $title,
                    'data-pjax' => '0',
                    'data-link' => $url
                ], $additionalOptions, $this->buttonOptions);


                return Html::a($title, $url, $options);
            };
        }
    }

}