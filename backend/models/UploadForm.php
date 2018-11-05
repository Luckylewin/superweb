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

    public $image_big;

    public $image_big_hover;

    public $media;

    public $list_icon;

    /**
     * @var UploadedFile
     */
    public $apk;

    public function rules()
    {
        return [
            [['image', 'image_big', 'image_hover', 'image_big_hover'], 'file', 'skipOnEmpty' => true, 'extensions' => ['jpg','png', 'gif']],
            ['apk', 'file', 'skipOnEmpty' => true, 'extensions' => ['apk']],
            ['media', 'file', 'skipOnEmpty' => true, 'extensions' => '*']
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