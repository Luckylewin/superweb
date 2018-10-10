<?php

namespace backend\models;

use common\components\Func;
use common\oss\Aliyunoss;
use Yii;
use yii\helpers\ArrayHelper;
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
            ['scheme_id','required', 'on' => 'set-scheme'],
            [['typeName', 'type', 'class'], 'required'],
            [['img'], 'string'],
            [['sort'], 'integer'],
            ['sort', 'default', 'value' => 0],
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
            'typeName' => Yii::t('backend', 'Name'),
            'type' => Yii::t('backend', 'Type'),
            'class' => Yii::t('backend', 'Package names'),
            'img' => Yii::t('backend', 'Icon'),
            'sort' => Yii::t('backend', 'Sort'),
            'scheme_id' => Yii::t('backend', 'Schemes'),
        ];
    }

    public function getScheme()
    {

        return Scheme::find()->where("id in ({$this->scheme_id})")->all();
    }

    public function getNewest()
    {
        return $this->hasOne(ApkDetail::className(), ['apk_ID' => 'ID'])
                    ->select(['ver', 'url', 'content', 'force_update', 'save_position'])
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

            // 删除管理表关系
            ApkToScheme::deleteAll(['apk_id' => $this->ID]);

        }
        return true;
    }

    public function scenario($value)
    {
        return [
            'set-scheme' => ['scheme_id'],
        ];

    }

    public function beforeValidate()
    {
        if ($this->scenario == 'set-scheme') {
             $this->setScheme($this->scheme_id);
        }

        return true;
    }


    public function afterSave($insert, $changedAttributes)
    {
        if (Admin::isSuperAdmin() == false) {
            $this->bindScheme();
        }

        return true;
    }

    /**
     * APK 管理员管理时 自动绑定关联关系
     */
    public function bindScheme()
    {
        $user = Admin::getCurrentUser();
        $scheme = ArrayHelper::getColumn($user->getScheme('id'), 'id');
        // 查看我拥有的方案号 是否与 apk 关联
        foreach ($scheme as $scheme_id) {
            $exist = ApkToScheme::find()->where(['apk_id' => $this->ID, 'scheme_id' => $scheme_id])->exists();
            if ($exist == false) {
                $ship = new ApkToScheme();
                $ship->scheme_id = $scheme_id;
                $ship->apk_id = $this->ID;
                $ship->save();

                $apk = ApkList::findOne($this->ID);
                if ($apk) {
                    $db_scheme_id= explode(',', $apk->scheme_id);
                    if (!in_array($scheme_id, $db_scheme_id)) {
                        $db_scheme_id[] = $scheme_id;
                        $apk->scheme_id = implode(',', $db_scheme_id);
                        $apk->save(false);
                    }
                }
            }
        }
    }

    /**
     * 管理设置关联
     * @param $scheme_ids
     * @return bool
     */
    public function setScheme($scheme_ids)
    {
        /**
         * @var $scheme_ids array
         */
        // 查找关联的订单号
        $dbData = ApkToScheme::find()->where(['apk_id' => $this->ID])->select('scheme_id')->column();

        // 删除 A-AnB
        $intersection = array_intersect($dbData, $scheme_ids);

        $aDiff = array_diff($dbData,  $intersection);
        if (!empty($aDiff)) {
            $ship = ApkToScheme::find()->where(['apk_id' => $this->ID])->andWhere(['in', 'scheme_id', $aDiff])->all();
            foreach ($ship as $_ship) {
                $_ship->delete();
            }
        }

        // 增加 B-AnB
        $bDiff = array_diff($scheme_ids, $intersection);

        if (!empty($bDiff)) {
             foreach ($bDiff as $scheme_id) {
                 $ship = new ApkToScheme();
                 $ship->scheme_id = $scheme_id;
                 $ship->apk_id = $this->ID;
                 $ship->save();
             }
        }


        if (!empty($this->scheme_id)) {
            $this->scheme_id = implode(',', $this->scheme_id);
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
                   if (strpos($apk->url, 'http://') !== false) {
                        return $apk->url;
                   } elseif (strpos($apk->url, '/') == 0) {
                       return Func::getAccessUrl($apk->url, 3600);
                   } else {
                        return Aliyunoss::getDownloadUrl($apk->url);
                   }

               }
               return null;
            }
        ];
    }


    public function getSchemes()
    {
        return $this->hasMany(Scheme::className(), ['id' => 'scheme_id'])
                    ->via('schemeItem');
    }

    public function getSchemeItem()
    {
        return $this->hasMany(ApkToScheme::className(), ['apk_id' => 'ID']);
    }

}
