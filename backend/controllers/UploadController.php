<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/5/16
 * Time: 10:14
 */

namespace backend\controllers;

use backend\models\UploadForm;
use common\components\Func;
use Yii;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;
use yii\helpers\Json;
use yii\helpers\Url;


class UploadController extends BaseController
{
    public $enableCsrfValidation = false;

    private $storagePath = '/home/upload/';

    public function setPath($dirName)
    {
        return Yii::getAlias($this->storagePath . $dirName) ;
    }

    public function getPath($filePath)
    {
        return Yii::getAlias($this->storagePath . $filePath);
    }

    /**
     * editor文件上传
     * @return string
     */
    public function actionEditorUpload()
    {
        $fileInstance = UploadedFile::getInstanceByName('file');
        $directory = $this->setPath('editor');
        $response = $this->upload($fileInstance, $directory);
        if (isset($response['files'])) {
            return Json::encode([
                'filelink' => $response['files'][0]['url'],
                'filename' => $response['files'][0]['name'],
            ]);
        }

        return Json::encode([$response]);

    }

    /**
     * 文件上传
     * @return string
     */
    public function actionImageUpload()
    {
        $model = new UploadForm();
        $field = Yii::$app->request->get('field', 'image');
        $imageFile = UploadedFile::getInstance($model, $field);
        $directory = $this->setPath('images') ;

        return Json::encode($this->upload($imageFile, $directory));
    }

    /**
     * 分片上传
     */
    public function actionChunkUpload()
    {
        //application/octet-stream
        header('Content-type: text/plain; charset=utf-8');
        //文件名
        $fileName = $_REQUEST['fileName'];
        //文件总大小
        $totalSize = $_REQUEST['totalSize'];
        //是否为末段
        $isLastChunk = $_REQUEST['isLastChunk'];
        //是否是第一次上传
        $isFirstUpload = $_REQUEST['isFirstUpload'];
        //相对路径
        $basicPath = "storage/uploads/vod-movie/" . $fileName;
        //绝对路径
        $fileSavePath = Yii::getAlias('@' . $basicPath);

        if ($_FILES['theFile']['error'] > 0) {
            $status = 500;
        } else {
            // 如果第一次上传的时候，该文件已经存在，则删除文件重新上传
            if ($isFirstUpload == '1' && file_exists($fileSavePath) && filesize($fileSavePath) == $totalSize) {
                unlink($fileSavePath);
            }
            // 否则继续追加文件数据
            if (!file_put_contents($fileSavePath, file_get_contents($_FILES['theFile']['tmp_name']), FILE_APPEND)) {
                $status = 501;
            } else {
                // 在上传的最后片段时，检测文件是否完整（大小是否一致）
                if ($isLastChunk === '1') {
                    if (filesize($fileSavePath) == $totalSize) {
                        $status = 200;
                    } else {
                        $status = 502;
                    }
                } else {
                    $status = 200;
                }
            }
        }

        echo json_encode(array(
            'path' => $basicPath,
            'status' => $status,
            'totalSize' => filesize($fileSavePath),
            'isLastChunk' => $isLastChunk,
            'Size' =>  $totalSize
        ));
    }

    /**
     * APK上传
     * @return string
     */
    public function actionApkUpload()
    {
        $model = new UploadForm();
        $imageFile = UploadedFile::getInstance($model, 'apk');
        $directory = $this->setPath('apk') ;
        $thumb = Yii::$app->request->hostInfo . '/statics/images/android-boot.png';

        return Json::encode($this->upload($imageFile, $directory, $thumb));
    }

    /**
     * @param UploadedFile $FileInstance
     * @param string $directory
     * @param boolean $thumb
     * @return array
     */
    public function upload($FileInstance, $directory, $thumb = false)
    {
        if (!is_dir($directory)) {
            FileHelper::createDirectory($directory);
            chmod($directory, 0777);
        }

        if ($FileInstance) {
            $fileName = $FileInstance->getBaseName() . '.' . $FileInstance->extension;
            $filePath = $directory . DIRECTORY_SEPARATOR . $fileName;

            if ($FileInstance->saveAs($filePath)) {
                $_path = explode('/', $directory);
                $path = DIRECTORY_SEPARATOR . end($_path) . DIRECTORY_SEPARATOR . $fileName;
                $expireUrl =Func::getAccessUrl($path);

                return [
                    'files' => [
                        [
                            'name' => $FileInstance->getBaseName(),
                            'size' => $FileInstance->size,
                            'path' => $path,
                            'url' => $expireUrl,
                            'thumbnailUrl' => $thumb ? $thumb : $expireUrl,
                            'deleteUrl' => Url::to(['upload/file-delete', 'path' => $path]),
                            'deleteType' => 'POST',
                        ],
                    ],
                ];
            }

        }
        return ['error' => '上传失败'];
    }


    /**
     * 删除文件
     * @param $path
     * @return string
     */
    public function actionFileDelete($path)
    {
        $this->enableCsrfValidation = false;

        $filePath = $this->getPath($path);

        if (is_file($filePath)) {
            unlink($filePath);
        }
        $output = [];
        return Json::encode($output);
    }



}