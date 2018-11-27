<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/11/27
 * Time: 8:56
 */

namespace console\jobs;


use backend\models\IptvTypeItem;
use backend\models\MultiLang;
use common\components\BaiduTranslator;
use common\models\VodList;

class TranslateJob
{
    public static function typeItem()
    {
       // 查询支持的语言
       $list = VodList::find()->where(['>', 'list_id', 0])->one();
       if (!empty($list)) {
           $supported_languages = json_decode($list->supported_language, true);
           $items = IptvTypeItem::find()->where(['is_show' => 1])->andFilterWhere(['>', 'exist_num', 0])->all();
           foreach ($items as $item) {
               if (MultiLang::find()->where(['table' => IptvTypeItem::tableName(), 'fid' => $item->id, 'field' => 'name'])->exists() == false) {
                    foreach ($supported_languages as $language) {
                         $multiLang = new MultiLang();
                         $multiLang->field = 'name';
                         $multiLang->fid   = $item->id;
                         $multiLang->table = IptvTypeItem::tableName();
                         $multiLang->language = $language;
                         if (is_numeric($item->name) == false) {
                             $to = BaiduTranslator::convertCode($language);
                             if ($to) {
                                 $multiLang->value = BaiduTranslator::translate($item->name, 'auto', $to);
                             }
                         } else {
                             $multiLang->value = $item->name;
                         }

                         $multiLang->save(false);
                         echo "新增翻译项" . $multiLang->value , PHP_EOL;
                         sleep(1);
                    }
               }
           }
       }

    }
}