<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\VodProfiles */

$this->title = $model->name;

?>
<style>
    .mth {min-width: 100px;background: #eeeeee}
</style>
<div class="vod-profiles-view">

    <h1><?= Html::encode($this->title) ?></h1>




    <table class="table table-bordered">

        <tr>
            <th class="mth"><?= $model->getAttributeLabel('id') ?></th>
            <td><?= $model->id ?></td>
            <th class="mth"><?= $model->getAttributeLabel('name') ?></th>
            <td><?= $model->name ?></td>
            <th class="mth"><?= $model->getAttributeLabel('alias_name') ?></th>
            <td><?= $model->alias_name ?></td>
        </tr>

        <tr>
            <th class="mth"><?= $model->getAttributeLabel('director') ?></th>
            <td><?= $model->director ?></td>
            <th class="mth"><?= $model->getAttributeLabel('screen_writer') ?></th>
            <td><?= $model->screen_writer ?></td>
            <th class="mth"><?= $model->getAttributeLabel('language') ?></th>
            <td><?= $model->language ?></td>
        </tr>
        <tr>
            <th class="mth"><?= $model->getAttributeLabel('area') ?></th>
            <td><?= $model->area ?></td>
            <th class="mth"><?= $model->getAttributeLabel('year') ?></th>
            <td><?= $model->year ?></td>
            <th class="mth"><?= $model->getAttributeLabel('date') ?></th>
            <td><?= $model->date ?></td>
        </tr>

        <tr>
            <th class="mth"><?= $model->getAttributeLabel('type') ?></th>
            <td><?= $model->type ?></td>
            <th class="mth"><?= $model->getAttributeLabel('tag') ?></th>
            <td><?= $model->tag ?></td>
            <th class="mth"><?= $model->getAttributeLabel('user_tag') ?></th>
            <td><?= $model->user_tag ?></td>
        </tr>



        <tr>
            <th class="mth"><?= $model->getAttributeLabel('douban_score') ?></th>
            <td><?= empty($model->douban_score)?'暂无':$model->douban_score; ?></td>
            <th class="mth"><?= $model->getAttributeLabel('tmdb_score') ?></th>
            <td><?= $model->tmdb_score??'暂无' ?></td>
            <th class="mth"><?= $model->getAttributeLabel('imdb_score') ?></th>
            <td><?= $model->imdb_score??'暂无' ?></td>
        </tr>

        <tr>
            <th class="mth"><?= $model->getAttributeLabel('douban_search') ?></th>
            <td>
                <?= $model->douban_search ? '√' : 'x' ?>
                <?php if($model->douban_id) echo Html::a('- 豆瓣链接' , "https://movie.douban.com/subject/{$model->douban_id}/?from=showing", ['target' => '_blank']) ?>
            </td>
            <th class="mth"><?= $model->getAttributeLabel('tmdb_search') ?></th>
            <td>
                <?= $model->tmdb_search ? '√' : 'x'  ?>
                <?php if($model->tmdb_id) echo Html::a('- tmdb链接' , "https://www.themoviedb.org/movie/{$model->tmdb_id}",['target' => '_blank']) ?>
            </td>
            <th class="mth"><?= $model->getAttributeLabel('imdb_search') ?></th>
            <td>
                <?= $model->imdb_search ? '√' : 'x'  ?>
                <?php if($model->imdb_id) echo Html::a('- imdb链接' , "https://www.imdb.com/title/{$model->imdb_id}/",['target' => '_blank']) ?>
            </td>
        </tr>


        <tr>
            <th class="mth"><?= $model->getAttributeLabel('cover') ?></th>
            <td><?= Html::a('封面图片',$model->cover,['target' => '_blank']) ?></td>
            <th class="mth"><?= $model->getAttributeLabel('image') ?></th>
            <td><?php if ($model->image ) echo Html::a('大图片',$model->image,['target' => '_blank']) ?></td>
            <th class="mth"><?= $model->getAttributeLabel('banner') ?></th>
            <td><?php if ($model->banner ) echo Html::a('横幅图片',$model->banner,['target' => '_blank']) ?></td>
        </tr>

        <tr>
            <th class="mth"><?= $model->getAttributeLabel('media_type') ?></th>
            <td ><?= $model->media_type ?></td>
            <th class="mth"><?= $model->getAttributeLabel('length') ?></th>
            <td ><?= $model->length ?></td>
            <th class="mth"><?= $model->getAttributeLabel('fill_status') ?></th>
            <td ><?= $model->getFillStatus() ?></td>
        </tr>

        <tr>
            <th class="mth"><?= $model->getAttributeLabel('actor') ?></th>
            <td colspan="5"><?= $model->actor ?></td>
        </tr>
        <tr>
            <th class="mth"><?= $model->getAttributeLabel('plot') ?></th>
            <td colspan="5"><?= $model->plot ?></td>
        </tr>



        <tr>
            <th class="mth"><?= $model->getAttributeLabel('comment') ?></th>
            <td colspan="5">
                <?php if($model->comment): ?>
                    <?php $comments = json_decode($model->comment, true); ?>
                        <?php if(!empty($comments)): ?>
                        <ol>
                            <?php foreach ($comments as $comment): ?>
                                <li><?= $comment ?></li>
                            <?php endforeach; ?>
                        </ol>
                        <?php endif; ?>
                <?php endif; ?>
            </td>
        </tr>
        </tbody>
    </table>

    <p>
        <?= Html::a(Yii::t('backend', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary col-md-12']) ?>
    </p>


</div>
