<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/9/27
 * Time: 10:45
 */

namespace backend\models\form;

use Yii;
use backend\models\Scheme;
use backend\models\VodToScheme;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class BindVodSchemeForm extends Model
{
    public $scheme_id;
    public $vod_id;

    public function rules()
    {
        return [
            [['vod_id', 'scheme_id'], 'required']
        ];
    }

    public function attributeLabels()
    {
        return [
            'vod_id' => '影片id',
            'scheme_id' => '方案号id',
        ];
    }

    public function init()
    {

        return parent::init();
    }

    /**
     *
     */
    public function save()
    {
        if ($this->validate()) {
            $allSchemeOptions = Scheme::getAll();
            $allSchemeOptions = ArrayHelper::getColumn($allSchemeOptions, 'id');

            // 全部 - 支持 = 不支持
            $nonsupport = array_diff($allSchemeOptions, $this->scheme_id);
            
            // 查找
            $nonsupportFromDB = VodToScheme::findByVodId($this->vod_id);
            
            if ($nonsupport != $nonsupportFromDB) {
                // 删除vod对应的方案号
                VodToScheme::deleteAll(['vod_id' => $this->vod_id]);
                $column = ['vod_id','scheme_id'];
                $row = [];
                if (!empty($nonsupport)) {
                    foreach ($nonsupport as $scheme_id) {
                        $row[] = [$this->vod_id, $scheme_id];
                    }

                    Yii::$app->db->createCommand()->batchInsert(VodToScheme::tableName(),  $column, $row)->execute();
                }

                return true;
            }
        }

        return true;
    }

}