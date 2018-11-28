<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/11/28
 * Time: 9:46
 */
namespace common\widgets\multilang;

use yii\base\Widget;

class MultiLangWidget extends Widget
{
    public $name = '多语言设置';
    public $table;
    public $field;
    public $id;
    public $options = [];

    protected static $runNum = 0;

    public function init()
    {
        $checks = ['name','table', 'field', 'id'];
        foreach ($checks as $field) {
            if (empty($this->$field)) {
                throw new \InvalidArgumentException("请配置<{$field}>参数");
            }
        }

        $this->options = array_merge([
            'class'       => 'btn btn-info',
            'data-target' => '#language-modal',
            'data-toggle' => 'modal',
            'data-id'     =>  $this->id,
            'data-table'  =>  $this->table,
            'data-field'  =>  $this->field
        ], $this->options);

        $this->options['class'] .= ' language';
    }


    public function run()
    {
        self::$runNum++;
        return $this->render('index', [
            'name'      => $this->name,
            'options'   => $this->options,
            'modalShow' => self::$runNum == 1 ? true : false
        ]);
    }
}