<?php
namespace common\widgets;

use Yii;

/**
 * Alert widget renders a message from session flash. All flash messages are displayed
 * in the sequence they were assigned using setFlash. You can set message as following:
 *
 * ```php
 * Yii::$app->session->setFlash('error', 'This is the message');
 * Yii::$app->session->setFlash('success', 'This is the message');
 * Yii::$app->session->setFlash('info', 'This is the message');
 * ```
 *
 * Multiple messages could be set as follows:
 *
 * ```php
 * Yii::$app->session->setFlash('error', ['Error 1', 'Error 2']);
 * ```
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @author Alexander Makarov <sam@rmcreative.ru>
 */
class Toastr extends \yii\bootstrap\Widget
{
    /**
     * @var array the alert types configuration for the flash messages.
     * This array is setup as $key => $value, where:
     * - $key is the name of the session flash variable
     * - $value is the bootstrap alert type (i.e. danger, success, info, warning)
     */
    public $alertTypes = [
        'error'   => 'error',
        'danger'  => 'error',
        'success' => 'success',
        'info'    => 'info',
        'warning' => 'warning'
    ];
    /**
     * @var array the options for rendering the close button tag.
     */
    public $closeButton = [];


    public function init()
    {
        parent::init();

        $session = Yii::$app->session;
        $flashes = $session->getAllFlashes();

        echo "<script>";

        foreach ($flashes as $type => $data) {

            if (isset($this->alertTypes[$type])) {
                $data = (array) $data;
                foreach ($data as $i => $message) {
                    $function = $this->alertTypes[$type];
                    $view = $this->getView();
                    if ($msgStr = json_decode($message,true)) {
                        $str = '<b>Excel导入错误信息:</b><br/>';
                        $num = 0;
                        foreach ($msgStr as $msg) {
                            if ($num < 20) {
                                $str .= "文件第{$msg['row']}行  {$msg['msg']}<br/>";
                            }
                            $num++;
                        }
                        $message = $str;
                    }
                    echo $js = "toastr.{$function}('{$message}');";
                    $view->registerJs($js);
                }
                $session->removeFlash($type);
            }
        }

      echo "</script>";
    }
}
