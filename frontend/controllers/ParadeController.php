<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/12/25
 * Time: 10:44
 */

namespace frontend\controllers;


class ParadeController extends BaseController
{
    public function actionNba()
    {
        $this->layout = false;

        return $this->render('nba');
    }
}