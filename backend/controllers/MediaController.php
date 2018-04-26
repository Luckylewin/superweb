<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/4/24
 * Time: 13:48
 */

namespace backend\controllers;

use common\models\VodList;
use Yii;
use common\models\Vod;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\UploadedFile;

class MediaController extends BaseController
{
    public function actionImageUpload()
    {
        $dir = Yii::$app->request->get('dir');
        $attribute = Yii::$app->request->get('attr');

        switch ($attribute)
        {
            case 'icon':
                $model = new VodList();
                break;
            case 'pic':
            case 'pic_bg':
            case 'pic_slide':
                $model = new Vod();
                break;
            default:
                return Json::encode(['error' => '上传失败']);
        }

        $imageFile = UploadedFile::getInstance($model, $attribute);

        $directory = Yii::getAlias('@storage/uploads/' . $dir) ;
        if (!is_dir($directory)) {
            FileHelper::createDirectory($directory);
        }

        if ($imageFile) {
            $uid = uniqid(time(), true);
            $fileName = $uid . '.' . $imageFile->extension;
            $filePath = $directory . DIRECTORY_SEPARATOR . $fileName;
            if ($imageFile->saveAs($filePath)) {
                $path = Url::to('storage/uploads'. DIRECTORY_SEPARATOR. $dir . DIRECTORY_SEPARATOR . $fileName, true );
                return Json::encode([
                    'files' => [
                        [
                            'name' => $imageFile->getBaseName(),
                            'size' => $imageFile->size,
                            'url' => $path,
                            'thumbnailUrl' => $path,
                            'deleteUrl' => Url::to(['media/image-delete', 'dir'=>$dir, 'name' => $fileName]),
                            'deleteType' => 'POST',
                        ],
                    ],
                ]);
            }
        }

        return Json::encode(['error' => '上传失败']);
    }

    public function actionImageDelete($dir, $name)
    {
        $this->enableCsrfValidation = false;

        $directory = Yii::getAlias('@storage/uploads/' . $dir);
        if (is_file($directory . DIRECTORY_SEPARATOR . $name)) {
            unlink($directory . DIRECTORY_SEPARATOR . $name);
        }

        $files = FileHelper::findFiles($directory);
        $output = [];
        foreach ($files as $file) {
            $fileName = basename($file);
            $path = '/storage/uploads/' . $dir . DIRECTORY_SEPARATOR . $fileName;
            $output['files'][] = [
                'name' => $fileName,
                'size' => filesize($file),
                'url' => $path,
                'thumbnailUrl' => $path,
                'deleteUrl' => 'image-delete?name=' . $fileName,
                'deleteType' => 'POST',
            ];
        }
        return Json::encode($output);
    }

}