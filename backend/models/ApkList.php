<?php

namespace backend\models;

use common\oss\Aliyunoss;
use Yii;
use yii\helpers\Url;
use yii\web\Linkable;

/**
 * This is the model class for table "apk_list".
 *
 * @property int $ID
 * @property string $typeName
 * @property string $type
 * @property string $class
 * @property string $img
 * @property int $sort
 * @property string $scheme_id
 */
class ApkList extends \yii\db\ActiveRecord implements Linkable
{
    public $dir = 'Android/apk/img/';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'apk_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['typeName', 'type', 'class','scheme_id'], 'required'],
            [['img'], 'string'],
            [['sort'], 'integer'],
            [['typeName', 'type', 'class'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'typeName' => 'APK名称',
            'type' => '类型',
            'class' => '包名',
            'img' => '图标',
            'sort' => '排序',
            'scheme_id' => '方案号',
        ];
    }

    public function beforeValidate()
    {
       if (parent::beforeValidate()) {
           if (!empty($this->scheme_id)) {
               if (is_array($this->scheme_id)) {
                   $this->scheme_id = implode(',', $this->scheme_id);
               }

           }
       }
       return true;
    }

    public function getScheme()
    {

        return Scheme::find()->where("id in ({$this->scheme_id})")->all();
    }

    public function getNewest()
    {
        return $this->hasOne(ApkDetail::className(), ['apk_ID' => 'ID'])
                    ->select(['ver', 'url', 'content', 'force_update'])
                    ->orderBy('apk_detail.ID desc')
                    ->limit(1);
    }

    public function getVersion()
    {
        return $this->hasMany(ApkDetail::className(), ['apk_ID' => 'ID']);
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            //删除子版本
            $data = $this->getVersion()->all();
            foreach ($data as $key => $ver) {
                $ver->delete();
            }
            if (!empty($this->img) && strpos($this->img, 'http://') === false) {
                try{
                    (new Aliyunoss())->delete($this->img);
                }catch (\Exception $e) {

                }
            }
        }
        return true;
    }

    public function getLinks()
    {
        // TODO: Implement getLinks() method.
        return [
            \yii\web\Link::REL_SELF => Url::to(['apk-list/view','id'=>$this->ID])
        ];
    }

    public function fields()
    {
        return [
            'typeName',
            'type',
            'class',
            'img',
            'sort',
            'scheme_id',
            'url' => function ($model) {
               if ($apk = ApkDetail::findOne(['apk_ID' => $this->ID])) {
                    return Aliyunoss::getDownloadUrl($apk->url);
               }
               return null;
            }
        ];
    }

}
