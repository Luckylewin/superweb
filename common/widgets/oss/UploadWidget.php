<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/3/14
 * Time: 14:55
 */

namespace common\widgets\oss;

use yii\bootstrap\Widget;
use yii\helpers\Url;

/**
 *
 * <?= \common\widgets\oss\UploadWidget::widget([
        'model' => $model,
        'form' => $form,
        'field' => 'title',
        'allowExtension' => [
        'image file' => 'jpg,png'
        ]
]); ?>
 *
 *
 * Class OssUploader
 * @package common\widgets
 */
class UploadWidget extends Widget
{
    //字段
    public $field;
    //activeForm
    public $form;
    //模型
    public $model;
    //保存目录
    public $dir;
    //是否展示图床
    public $bed = false;

    /**
     * 文件大小最大值
     * @var
     */
    public $maxSize;

    /**
     * 允许格式
     * [{ title : "apk files", extensions : "application/vnd.android.package-archive,.apk,apk" }]
     * @var
     */
    public $allowExtension;

    public $defaultMaxSize = "200mb";

    public $defaultAllowExtensions = '[
                                        { title : "Image files", extensions : "image/jpg,image/jpeg,image/png,image/bmp" },
                                        { title : "Zip files", extensions : "zip,rar" },
                                        { title : "apk files", extensions : "app,apk,bin" }
                                      ]';

    public function beforeRun()
    {
        if (parent::beforeRun()) {
            if (!empty($this->allowExtension) && is_array($this->allowExtension)) {
                $extensionArray = [];
                foreach ($this->allowExtension as $title => $extension) {
                    $extensionArray[] = [
                        "title" => $title,
                        "extensions" => $extension
                    ];
                }
                $this->allowExtension = str_replace('"title"','title', json_encode($extensionArray));
            } else {
                $this->allowExtension = $this->defaultAllowExtensions;
            }
            if (empty($this->maxSize)) {
                $this->maxSize = $this->defaultMaxSize;
            }
            
            if ($this->model->dir) {
                $this->dir = $this->model->dir;
            } elseif (empty($this->dir)) {
                $this->dir = 'user-dir/' . date('Ymd') . '/';
            }
        }
        return true;
    }

    public function run()
    {
        $serverUrl = Url::to(['api/oss-upload','dir' => $this->dir]);

        return $this->render('upload-form',[
            'serverUrl' => $serverUrl,
            'maxSize' => $this->maxSize,
            'mime_types' => $this->allowExtension,
            'form' => $this->form,
            'field' => $this->field,
            'model' => $this->model,
            'bed' => $this->bed
        ]);
    }
}