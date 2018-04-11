<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 2018/1/19
 * Time: 15:00
 */

namespace common\widgets;


use yii\widgets\Block;

class Cssblock extends Block
{
    /**
     * @var null
     */
    public $key = null;
    /**
     * @var array $options the HTML attributes for the style tag.
     */
    public $options = [];

    /**
     * Ends recording a block.
     * This method stops output buffering and saves the rendering result as a named block in the view.
     */
    public function run()
    {
        $block = ob_get_clean();
        if ($this->renderInPlace) {
            throw new \Exception("not implemented yet ! ");
            // echo $block;
        }
        // $block = trim($block) ;
        $block = static::unwrapStyleTag($block);

        $this->view->registerCss($block, $this->options, $this->key);
    }

    /**
     * @param $cssBlock
     * @return string
     */
    public static function unwrapStyleTag($cssBlock)
    {
        $block = trim($cssBlock);

        $cssBlockPattern = '|^<style[^>]*>(?P<block_content>.+?)</style>$|is';
        if (preg_match($cssBlockPattern, $block, $matches)) {
            $block = $matches['block_content'];
        }
        return $block;
    }
}