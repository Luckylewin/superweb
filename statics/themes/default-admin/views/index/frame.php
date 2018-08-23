<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Url;
use backend\assets\AppAsset as AppAsset;
use backend\models\Menu;

AppAsset::register($this);
$allMenus = Menu::getActualMenu();
$username = Yii::$app->user->isGuest == false ? Yii::$app->user->identity->username : '' ;

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">

    <title><?= isset(Yii::$app->params['basic']['sitename']) ? Yii::$app->params['basic']['sitename'] :'' ?></title>

    <meta name="keywords" content="后台">
    <meta name="description" content="">

    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->

    <link rel="shortcut icon" href="/statics/iptv.ico">
    <link href="/statics/themes/default-admin/plugins/bootstrap-v3.3/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="/statics/themes/default-admin/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
    <link href="/statics/themes/default-admin/css/animate.css" rel="stylesheet">
    <link href="/statics/themes/default-admin/css/style.css?v=4.1.0" rel="stylesheet">
</head>

<body class="fixed-sidebar full-height-layout gray-bg" style="overflow:hidden">
<div id="wrapper">
    <!--左侧导航开始-->
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="nav-close">
            <i class="fa fa-times-circle"></i>
        </div>
        <div class="sidebar-collapse">
            <ul class="nav" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element">
                        <!--<span><img alt="image" class="img-circle" src="/statics/images/admin.png" /></span>-->
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="clear">
                               <span class="block m-t-xs"><strong class="font-bold"><?= $username ?></strong></span>
                                <span class="text-muted text-xs block"><?= isset($rolename)?$rolename:'管理员'; ?><b class="caret"></b></span>
                                </span>
                        </a>
                        <ul class="dropdown-menu animated fadeInRight m-t-xs">

                             <li>
                                 <a class="J_menuItem" href="<?= Url::to(['admin/reset-password']) ?>"><?= Yii::t('backend', 'Change Password') ?></a>
                             </li>

                            <li class="divider"></li>
                            <li><a href="<?= Url::to(['site/logout']) ?>"><?= Yii::t('backend', 'Sign Out') ?></a>
                            </li>
                        </ul>
                    </div>
                    <div class="logo-element">H+
                    </div>
                </li>

                <?php
                foreach ($allMenus as $menus) {
                    ?>
                    <li >
                        <a href="#"><i class="fa <?=$menus['icon_style'];?>"></i><span><?= Yii::t('backend', $menus['name']);?></span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <?php
                            if(!isset($menus['_child'])) break;
                            foreach ($menus['_child'] as $menu) {
                                $menuArr = explode('/', $menu['url']);
                                ?>

                                <li><a class="J_menuItem" href="<?=Url::to([$menu['url']]);?>"><?= Yii::t('backend', $menu['name']);?></a></li>

                            <?php }?>
                        </ul>
                    </li>
                <?php } ?>


            </ul>
        </div>
    </nav>
    <!--左侧导航结束-->
    <!--右侧部分开始-->
    <div id="page-wrapper" class="gray-bg dashbard-1">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header"><a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                    <form role="search" class="navbar-form-custom" >
                        <div class="form-group">
                            <input type="text" placeholder="<?= Yii::t('backend', 'Please enter what you need to find…') ?>" class="form-control" name="top-search" id="top-search">
                        </div>
                    </form>
                </div>
                <ul class="nav navbar-top-links navbar-right">

                    <li class="dropdown">
                        <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                            <i class="fa fa-language"></i> <?= Yii::t('backend', 'Language') ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="<?= Url::to(['index/language', 'lang' => 'en-US']) ?>">
                                    <div>
                                        <i class="fa fa-hand-o-right fa-fw"></i> English
                                    </div>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="<?= Url::to(['index/language', 'lang' => 'zh-CN']) ?>">
                                    <div>
                                        <i class="fa fa-hand-o-right fa-fw"></i> 简体中文
                                    </div>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="<?= Url::to(['index/language', 'lang' => 'vi-VN']) ?>">
                                    <div>
                                        <i class="fa fa-hand-o-right fa-fw"></i> Vietnamese
                                    </div>
                                </a>
                            </li>

                        </ul>
                    </li>

                    <li class="dropdown hidden-xs">
                        <a class="right-sidebar-toggle" aria-expanded="false">
                            <i class="fa fa-tasks"></i> <?= Yii::t('backend', 'Themes') ?>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="row content-tabs">
            <button class="roll-nav roll-left J_tabLeft"><i class="fa fa-backward"></i>
            </button>
            <nav class="page-tabs J_menuTabs">
                <div class="page-tabs-content">
                    <a href="javascript:void(0);" class="active J_menuTab" data-id="<?=Url::to([isset($allMenus[0]['url'])?$allMenus[0]['url']:'']);?>"><?= Yii::t('backend', 'Home') ?></a>
                </div>
            </nav>
            <button class="roll-nav roll-right J_tabRight"><i class="fa fa-forward"></i>
            </button>
            <div class="btn-group roll-nav roll-right">
                <button class="dropdown J_tabClose" data-toggle="dropdown"><?= Yii::t('backend', 'Close') ?><span class="caret"></span>

                </button>
                <ul role="menu" class="dropdown-menu dropdown-menu-right">
                    <li class="J_tabShowActive"><a><?= Yii::t('backend', 'Locate the current tab') ?></a>
                    </li>
                    <li class="divider"></li>
                    <li class="J_tabCloseAll"><a><?= Yii::t('backend', 'Close all tabs') ?></a>
                    </li>
                    <li class="J_tabCloseOther"><a><?= Yii::t('backend', 'Close other tabs') ?></a>
                    </li>
                </ul>
            </div>
            <a href="<?= Url::to(['site/logout']) ?>" class="roll-nav roll-right J_tabExit"><i class="fa fa fa-sign-out"></i> <?= Yii::t('backend', 'leave') ?></a>
        </div>
        <div class="row J_mainContent" id="content-main">
            <iframe class="J_iframe" name="iframe0" width="100%" height="100%" src="<?= Url::to('/admin.php?r=index%2Findex')?>" frameborder="0" data-id="<?= Url::to('/admin.php?r=index%2Findex')?>" seamless></iframe>
        </div>
        <div class="footer">
            <div class="pull-right">&copy; Powered by  <a href="http://www.yiiframework.com/" target="_blank">Yii Framework</a>
            </div>
        </div>
    </div>
    <!--右侧部分结束-->
    <!--右侧边栏开始-->
    <div id="right-sidebar">
        <div class="sidebar-container">

            <ul class="nav nav-tabs navs-3">

                <li class="active">
                    <a data-toggle="tab" href="#tab-1">
                        <i class="fa fa-gear"></i> <?= Yii::t('backend', 'Themes') ?>
                    </a>
                </li>

            </ul>

            <div class="tab-content">
                <div id="tab-1" class="tab-pane active">
                    <div class="sidebar-title">
                        <h3> <i class="fa fa-comments-o"></i> <?= Yii::t('backend', 'Theme settings') ?></h3>
                        <small><i class="fa fa-tim"></i> <?= Yii::t('backend', 'These settings will be saved locally and will be applied directly the next time you open them.') ?></small>
                    </div>
                    <div class="skin-setttings">
                        <div class="title"><?= Yii::t('backend', 'Theme settings') ?></div>
                        <div class="setings-item">
                            <span><?= Yii::t('backend', 'Collapse the left menu') ?></span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="collapsemenu">
                                    <label class="onoffswitch-label" for="collapsemenu">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="setings-item">
                            <span><?= Yii::t('backend', 'Fixed top') ?></span>

                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="fixednavbar" class="onoffswitch-checkbox" id="fixednavbar">
                                    <label class="onoffswitch-label" for="fixednavbar">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="setings-item">
                                <span>

                    </span>

                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="boxedlayout" class="onoffswitch-checkbox" id="boxedlayout">
                                    <label class="onoffswitch-label" for="boxedlayout">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="title"><?= Yii::t('backend', 'Skin selection') ?></div>
                        <div class="setings-item default-skin nb">
                                <span class="skin-name ">
                         <a href="#" class="s-skin-0">
                             <?= Yii::t('backend', 'Default skin') ?>
                         </a>
                    </span>
                        </div>
                        <div class="setings-item blue-skin nb">
                                <span class="skin-name ">
                        <a href="#" class="s-skin-1">
                            <?= Yii::t('backend', 'Blue theme') ?>
                        </a>
                    </span>
                        </div>
                        <div class="setings-item yellow-skin nb">
                                <span class="skin-name ">
                        <a href="#" class="s-skin-3">
                            <?= Yii::t('backend', 'Yellow/purple theme') ?>
                        </a>
                    </span>
                        </div>
                    </div>
                </div>


            </div>

        </div>
    </div>

</div>

<!-- 全局js -->
<script src="/statics/themes/default-admin/js/jquery/jquery.min.js?v=2.1.4"></script>
<script src="/statics/themes/default-admin/plugins/bootstrap-v3.3/bootstrap.min.js?v=3.3.6"></script>
<script src="/statics/themes/default-admin/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="/statics/themes/default-admin/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="/statics/themes/default-admin/plugins/layer/layer.min.js"></script>

<!-- 自定义js -->
<script src="/statics/themes/default-admin/js/hplus.js?v=4.1.0"></script>
<script type="text/javascript" src="/statics/themes/default-admin/js/contabs.js"></script>

<!-- 网页加载进度条插件 -->
<script src="/statics/themes/default-admin/plugins/pace/pace.min.js"></script>

</body>

</html>
