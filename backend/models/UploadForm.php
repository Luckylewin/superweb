<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/5/16
 * Time: 9:54
 */
namespace backend\models;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $image;

    public $image_hover;

    /**
     * @var UploadedFile
     */
    public $apk;

    public function rules()
    {
        return [
            ['image', 'file', 'skipOnEmpty' => true, 'extensions' => ['jpg','png', 'gif']],
            ['image_hover', 'file', 'skipOnEmpty' => true, 'extensions' => ['jpg','png', 'gif']],
            ['apk', 'file', 'skipOnEmpty' => true, 'extensions' => ['apk']]
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            if ($this->image) {
                $this->image->saveAs('/home/upload/images' . $this->image->baseName . '.' . $this->image->extension);
            }
            if ($this->image) {
                $this->apk->saveAs('/home/upload/apk' . $this->apk->baseName . '.' . $this->apk->extension);
            }
        }
    }

}