<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/12/14
 * Time: 16:46
 */

namespace common\widgets;

use \yii\helpers\Url;
use yii\base\Widget;
use yii\helpers\Html;

class frameButton extends Widget
{
    public $url;
    public $title;
    public $options = [];
    public $content;
    public $icon;

    public function run()
    {
        $setting = [
            'title' => $this->content,
            'data-title' => $this->content,
            'data-link' => $this->url,
        ];

        $options = array_merge($setting, $this->options);

        if (!isset($options['class'])) {
            $options['class'] = 'btn btn-default btn-sm frame-open';
        } else {
            if (strpos($options['class'], 'frame-open') === false) {
                $options['class'] .= ' frame-open';
            }
        }

        if ($this->title) {
            $options['data-title'] = $this->title;
            $options['title'] = $this->title;
        }

        if ($this->icon) {
            $options['data-title'] = $this->content = Html::tag('i', ' ' . $options['data-title'], ['class' => 'fa '. $this->icon]);

        }

        $js=<<<JS

$(document).on('click', '.frame-open', function() {
  var url = $(this).data('link');
  var title = $(this).data('title');
  
  layer.myWindows(url, title)
       
  return false;
});

JS;
        $this->getView()->registerJs($js);

        return Html::button($this->content, $options);
    }
}