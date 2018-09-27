<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/7/30
 * Time: 16:53
 */

namespace backend\models\form;

use backend\models\Admin;
use backend\models\AdminToScheme;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class BindSchemeForm extends Model
{
    public $scheme_id;
    public $admin_id;

    public function rules()
    {
        return [
            [['admin_id', 'scheme_id'], 'required']
        ];
    }

    public function attributeLabels()
    {
       return [
           'admin_id' => '关联帐号',
           'scheme_id' => '方案号',
       ];
    }

    public function init()
    {
        $admin = AdminToScheme::find()->where(['scheme_id' => $this->scheme_id])->all();
        if ($admin) {
            $this->admin_id = ArrayHelper::getColumn($admin, 'admin_id');
        }
        return parent::init();
    }

    /**
     *
     */
    public function save()
    {
        if ($this->validate()) {
            if (!empty($this->admin_id)) {

                // 查找关联的
                $dbData = AdminToScheme::find()->where(['scheme_id' => $this->scheme_id])->select('admin_id')->all();
                $dbData = ArrayHelper::getColumn($dbData, 'admin_id');

                // 删除 A-AnB
                $intersection = array_intersect($dbData, $this->admin_id);
                $aDiff = array_diff($dbData,  $intersection);
                if (!empty($aDiff)) {
                    foreach ($aDiff as $diff) {
                        AdminToScheme::deleteAll(['scheme_id' => $this->scheme_id, 'admin_id' => $diff]);
                    }
                }

                // 新增 B-AnB
                $bDiff = array_diff($this->admin_id, $intersection);
                if (!empty($bDiff)) {
                    foreach ($bDiff as $admin_id) {
                        $ship = new AdminToScheme();
                        $ship->scheme_id = $this->scheme_id;
                        $ship->admin_id = $admin_id;
                        $ship->save(false);
                    }
                }

            }
        }

        return true;
    }

    public function getAdmin()
    {
        $admin = Admin::find()->asArray()->all();
        if (!empty($admin)) {
            $admin = ArrayHelper::map($admin, 'id', 'username');
            return $admin;
        }

        return $admin;
    }

}