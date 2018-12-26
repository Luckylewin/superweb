<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "sys_vod_profiles".
 *
 * @property int $id
 * @property string $name 影片名称
 * @property string $alias_name 别名
 * @property string $director 导演
 * @property int $screen_writer 编剧
 * @property string $actor 演员
 * @property string $area 地区
 * @property string $language 语言
 * @property string $type 类型
 * @property string $tag 标签
 * @property string $user_tag 用户标签
 * @property string $plot 剧情
 * @property string $year 发行年份
 * @property string $date 发行日期
 * @property int $imdb_id imdb id
 * @property string $imdb_score imdb 评分
 * @property int $tmdb_id the moviedb 评分
 * @property string $tmdb_score the moviedb
 * @property int $douban_id 豆瓣id
 * @property string $douban_score 豆瓣评分
 * @property int $douban_voters 评分人数
 * @property string $length 时长
 * @property string $cover 封面
 * @property string $image 大图
 * @property string $banner banner 图
 * @property string $comment 影评
 * @property int $fill_status 数据完整状态
 * @property int $douban_search 豆瓣是否查询过的状态
 * @property int $imdb_search IMDB是否查询过的状态
 * @property int $tmdb_search The Movie Db 是否查询过的状态
 * @property int $profile_language 资料语言
 * @property int $media_type 所属分类
 */
class VodProfiles extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_vod_profiles';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tmdb_id', 'douban_id', 'douban_voters'], 'integer'],
            [['type','tag','user_tag', 'actor', 'name', 'alias_name','director','language','area','screen_writer'], 'string', 'max' => 255],
            [['year'], 'string', 'max' => 4],
            [['date'], 'string', 'max' => 10],
            [['imdb_score', 'tmdb_score', 'douban_score'], 'string', 'max' => 3],
            [['length'], 'string', 'max' => 6],
            [['cover', 'banner', 'image'], 'string', 'max' => 255],
            [['fill_status','comment','plot','tmdb_search', 'imdb_search','douban_search','profile_language','tag','user_tag','media_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'alias_name' => 'Alias Name',
            'director' => 'Director',
            'screen_writer' => 'Screenwriter',
            'actor' => 'Actor',
            'area' => 'Area',
            'language' => 'Language',
            'type' => 'Type',
            'tab' => 'Tab',
            'plot' => 'Plot',
            'year' => 'Year',
            'date' => 'Date',
            'imdb_id' => 'Imdb ID',
            'imdb_score' => 'Imdb Score',
            'tmdb_id' => 'Tmdb ID',
            'tmdb_score' => 'Tmdb Score',
            'douban_id' => 'Douban ID',
            'douban_score' => 'Douban Score',
            'length' => 'Length',
            'cover' => 'Cover',
            'banner' => 'Banner',
            'comment' => 'Comment',
            'fill_status' => 'Fill Status',
        ];
    }

    public static function findByName($name)
    {
        $profile = self::find()->where(['name' => $name])->one();
        if ($profile) {
            return [
                'vod_name' => $profile->name,
                'vod_title' => $profile->alias_name,
                'vod_director' => $profile->director,
                'vod_actor' => $profile->actor,
                'vod_area' => $profile->area,
                'vod_language' => $profile->language,
                'vod_type' => $profile->type . ',' . $profile->tab,
                'vod_content' => $profile->plot,
                'vod_year' => $profile->year,
                'vod_filmtime' => $profile->date,
                'vod_imdb_id' => $profile->imdb_id,
                'vod_imdb_score' => $profile->imdb_score,
                'vod_douban_id' => $profile->douban_id,
                'vod_douban_score' => $profile->douban_score,
                'vod_length' => $profile->length,
                'vod_pic' => $profile->cover,
                'vod_pic_slide' => $profile->banner,
                'vod_fill_flag' => $profile->fill_status
            ];
        }

        return false;
    }
}
