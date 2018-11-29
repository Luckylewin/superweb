<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/11/27
 * Time: 8:56
 */

namespace console\jobs;


use backend\models\IptvType;
use backend\models\IptvTypeItem;
use backend\models\MultiLang;
use common\components\BaiduTranslator;
use common\models\VodList;


class TranslateJob
{
    private static function translateType($items, $field, $tableName)
    {
        if (empty($items)) {
            return false;
        }

        // 查询支持的语言
        $supported_languages = VodList::getLanguages();

        foreach ($items as $item) {
            foreach ($supported_languages as $language) {
                $exist = MultiLang::find()->where([
                    'table'    => $tableName,
                    'fid'      => $item->id,
                    'field'    => $field,
                    'language' => $language,
                ])->one();

                if ($exist == false) {
                    $multiLang = new MultiLang();
                    $multiLang->field = 'name';
                    $multiLang->fid   = $item->id;
                    $multiLang->table = $tableName;
                    $multiLang->language = $language;
                    $multiLang->origin   = $item->$field;

                    if (preg_match('/^\d+$/', $item->name)) {
                        $multiLang->value = $item->$field;
                    } else {
                        $to = BaiduTranslator::convertCode($language);
                        if ($to) {
                            $multiLang->value = BaiduTranslator::translate($item->$field, 'auto', $to);
                        }
                        sleep(1);
                    }

                    $multiLang->save(false);
                    echo "新增翻译项 " . $item->$field . '=>' . $multiLang->value , PHP_EOL;
                }
            }
        }

        return true;
    }

    public static function iptvType()
    {
        $items = IptvType::find()->where(['is_show' => 1])->all();
        self::translateType($items,'field', IptvType::tableName());
    }

    public static function typeItem()
    {
        $items = IptvTypeItem::find()->where(['is_show' => 1])->andFilterWhere(['>', 'exist_num', 0])->all();
        self::translateType($items,'name', IptvTypeItem::tableName());
    }
}