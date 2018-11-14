<?php

namespace common\models;

use backend\models\IptvType;
use backend\models\IptvTypeItem;
use backend\models\PlayGroup;
use backend\models\VodTypeMap;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\web\Link;
use yii\web\Linkable;
use Yii;

/**
 * This is the model class for table "iptv_vod".
 *
 * @property int $vod_id 影片id
 * @property int $vod_cid 影片分类
 * @property string $vod_name 影片名称
 * @property string $vod_ename 影片别名
 * @property string $vod_title 影片副标
 * @property string $vod_keywords 影片TAG
 * @property string $vod_type 扩展分类
 * @property string $vod_actor 主演
 * @property string $vod_director 导演
 * @property string $vod_content 影片简介
 * @property string $vod_pic 海报剧照
 * @property string $vod_pic_bg 背景图片
 * @property string $vod_pic_slide 轮播图片
 * @property string $vod_area 发行地区
 * @property string $vod_language 语言
 * @property int $vod_year 发行年份
 * @property string $vod_continu 连载信息
 * @property int $vod_total 总共集数
 * @property int $vod_isend 是否完结
 * @property int $vod_addtime 更新日期
 * @property int $vod_filmtime 上映日期
 * @property int $vod_hits 总人气
 * @property int $vod_hits_day 日人气
 * @property int $vod_hits_week 周人气
 * @property int $vod_hits_month 月人气
 * @property int $vod_stars 推荐星级
 * @property int $vod_status 影片状态（0隐藏1显示）
 * @property int $vod_up 支持
 * @property int $vod_down 反对
 * @property int $vod_ispay 点播权限（0免费1VIP）
 * @property int $vod_price 单片付费
 * @property int $vod_trysee 影片试看
 * @property string $vod_play 播放器选择
 * @property string $vod_server 服务器地址
 * @property string $vod_url 播放地址
 * @property string $vod_inputer 录入编辑
 * @property string $vod_reurl 来源标识
 * @property string $vod_origin_url 来源url
 * @property string $vod_jumpurl 跳转URL
 * @property string $vod_letter 首字母
 * @property string $vod_skin 独立模板
 * @property string $vod_gold 评分值
 * @property int $vod_golder 评分人数
 * @property int $vod_length 影片时长
 * @property string $vod_weekday 节目周期
 * @property string $vod_series 影片系列(如“变形金刚”1、2、3部ID分别为77，88，99则每部影片此处填写为77,88,99；或将每部影片都填“变形金刚”（推荐）)
 * @property int $vod_copyright 版权跳转：
 * @property string $vod_state 资源类别(正片|预告片|花絮)
 * @property string $vod_version 版本(高清版|剧场版|抢先版|OVA|TV|影院版)
 * @property int $vod_douban_id 豆瓣ID
 * @property string $vod_douban_score 豆瓣评分
 * @property string $vod_imdb_id imdb ID
 * @property string $vod_imdb_score imdb 评分
 * @property string $vod_scenario 影片剧情
 * @property string $vod_home 是否推荐到首页
 * @property string $vod_multiple 是否有多集
 * @property string $vod_fill_flag 是否完善数据(爬虫爬过)
 * @property string $sort 排序

 */
class Vod extends \yii\db\ActiveRecord implements Linkable
{
    public $is_buy;
    public $pic;
    public $pic_bg;
    public $pic_slide;


    const LINK_RECOMMEND = 'recommend';
    const LINK_GROUPLINK = 'groupLinks';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'iptv_vod';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vod_cid', 'vod_name'], 'required'],
            [['vod_cid', 'vod_year', 'vod_total', 'vod_addtime', 'vod_hits', 'vod_hits_day', 'vod_hits_week', 'vod_hits_month', 'vod_up', 'vod_down', 'vod_price', 'vod_trysee', 'vod_golder', 'vod_copyright', 'vod_douban_id'], 'integer'],
            [['vod_content', 'vod_url', 'vod_scenario', 'vod_origin_url'], 'string'],
            [['vod_gold', 'vod_douban_score'], 'number'],
            [['vod_name', 'vod_length'], 'string', 'max' => 100],
            [['vod_ename', 'vod_title', 'vod_keywords', 'vod_actor', 'vod_director', 'vod_pic', 'vod_pic_bg', 'vod_pic_slide', 'vod_play', 'vod_server', 'vod_reurl'], 'string', 'max' => 255],
            [['vod_area', 'vod_language'], 'string', 'max' => 30],
            [['vod_continu', 'vod_filmtime'], 'string', 'max' => 20],
            [['vod_inputer', 'vod_skin', 'vod_state', 'vod_version'], 'string', 'max' => 30],
            [['vod_jumpurl'], 'string', 'max' => 150],
            [['vod_letter'], 'string', 'max' => 2],
            [['vod_weekday'], 'string', 'max' => 60],
            [['vod_series'], 'string', 'max' => 120],
            [['vod_home', 'pic', 'vod_stars', 'vod_ispay', 'vod_fill_flag', 'sort', 'vod_imdb_id', 'vod_imdb_score', 'vod_type'], 'safe'],
            [['vod_up', 'vod_down', 'vod_hits', 'vod_hits_day', 'vod_hits_month', 'vod_hits_week', 'vod_multiple'],'default', 'value' => 0],
            ['vod_total', 'default', 'value' => '1']
        ];
    }

    public function beforeDelete()
    {
        $data = $this->getGroups();

        if ($data) {
            $groups = $data->all();
            if ($groups) {
                foreach ($groups as $group) {
                    $group->delete();
                }
            }
        }

        return true;
    }

    public function beforeSave($insert)
    {
        parent::beforeSave($insert);

        if (!$this->vod_trysee) {
            $vodList = VodList::findOne($this->vod_cid);
            $this->vod_trysee = $vodList->list_trysee;
        }

        if (!$this->vod_price) {
            $vodList = VodList::findOne($this->vod_cid);
            $this->vod_price = $vodList->list_price;
        }

        return true;
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'vod_id'        => Yii::t('backend', 'id'),
            'vod_cid'       => Yii::t('backend', 'Genre Name'),
            'vod_name'      => Yii::t('backend', 'Movie name'),
            'vod_ename'     => Yii::t('backend', 'Video alias'),
            'vod_title'     => Yii::t('backend', 'subtitle'),
            'vod_keywords'  => Yii::t('backend', 'TAG'),
            'vod_type'      => Yii::t('backend', 'Extended Genre'),//喜剧 爱情 恐怖 动作 科幻 剧情 战争 警匪 犯罪 动画 奇幻 武侠 冒险 枪战 恐怖 悬疑 惊悚 经典 青春文艺 微电影 古装 历史运动 农村 儿童 网络电影
            'vod_actor'     => Yii::t('backend', 'Main actor'),
            'vod_director'  => Yii::t('backend', 'director'),
            'vod_content'   => Yii::t('backend', 'Movie introduction'),
            'vod_pic'       => Yii::t('backend', 'Poster stills'),
            'vod_pic_bg'    => Yii::t('backend', 'Background picture'),
            'vod_pic_slide' => Yii::t('backend', 'Carousel picture'),
            'vod_area'      => Yii::t('backend', 'Distribution area'), //内地 美国 香港 台湾 韩国 日本 法国 英国 德国 泰国 印度 欧洲 东南亚 其他
            'vod_language'  => Yii::t('backend', 'Language'),// 国语 英语 粤语 闽南语 韩语 日语 其它
            'vod_year'      => Yii::t('backend', 'Release year'),
            'vod_continu'   => Yii::t('backend', 'Serial information'),
            'vod_total'     => Yii::t('backend', 'Total Episodes'),
            'vod_isend'     => Yii::t('backend', 'End Status'),
            'vod_addtime'   => Yii::t('backend', 'Created Time'),
            'vod_filmtime'  => Yii::t('backend', 'Release date'),
            'vod_hits'      => Yii::t('backend', 'Total popularity'),
            'vod_hits_day'  => Yii::t('backend', 'Popularity in the day'),
            'vod_hits_week' => Yii::t('backend', 'Popularity in the week'),
            'vod_hits_month'=> Yii::t('backend', 'Popularity of the month'),
            'vod_stars'     => Yii::t('backend', 'Recommended Star'),
            'vod_status'    => Yii::t('backend', 'Status'),//（0隐藏1显示）
            'vod_up'        => Yii::t('backend', 'like'),
            'vod_down'      => Yii::t('backend', 'dislike'),
            'vod_ispay'     => Yii::t('backend', 'Permission'),
            'vod_price'     => Yii::t('backend', 'gold'),
            'vod_trysee'    => Yii::t('backend', 'Free Experience Time'),
            'vod_play'      => Yii::t('backend', 'Player selection'),
            'vod_server'    => Yii::t('backend', 'Server address'),
            'vod_url'       => Yii::t('backend', 'Play address'),
            'vod_inputer'   => Yii::t('backend', 'Creator'),
            'vod_reurl'     => Yii::t('backend', 'Source identifier'),
            'vod_jumpurl'   => Yii::t('backend', 'Jump URL'),
            'vod_letter'    => Yii::t('backend', 'First Letter'),
            'vod_skin'      => Yii::t('backend', 'Independent template'),
            'vod_gold'      => Yii::t('backend', 'Rating'),
            'vod_golder'    => Yii::t('backend', 'Total number of ratings'),
            'vod_length'    => Yii::t('backend', 'Movie duration'),
            'vod_weekday'   => Yii::t('backend', 'Program cycle'),
            'vod_series'    => Yii::t('backend', 'Film series'),//(如“变形金刚”1、2、3部ID分别为77，88，99则每部影片此处填写为77,88,99；或将每部影片都填“变形金刚”（推荐）)
            'vod_copyright' => Yii::t('backend', 'Copyright jump'),
            'vod_state'     => Yii::t('backend', 'Resource Type'),//(正片|预告片|花絮)
            'vod_version'   => Yii::t('backend', 'Version'),//(高清版|剧场版|抢先版|OVA|TV|影院版)
            'vod_douban_id' => Yii::t('backend', 'Douban ID'),
            'vod_scenario'  => Yii::t('backend', 'Plot'),
            'vod_home'      => Yii::t('backend', 'Whether to recommend to the home page'),
            'vod_multiple'  => Yii::t('backend', 'Is multi-set'),
            'vod_imdb_id'   => 'IMDb ID',
            'vod_fill_flag' => Yii::t('backend', 'Data fill flag'),
            'sort'          => Yii::t('backend', 'Sort'),
            'vod_origin_url'   => Yii::t('backend', 'Origin Url'),
            'vod_imdb_score'   => Yii::t('backend', 'IMDb Ratting'),
            'vod_douban_score' => Yii::t('backend', 'Douban Ratting'),

        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'vod_addtime',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['vod_addtime'],
                ],
                'value' => time()
            ]
        ];
    }

    public function fields()
    {
        return [
            'vod_id',
            'vod_cid' ,
            'vod_name',
            'vod_ename',
            'vod_type',//喜剧 爱情 恐怖 动作 科幻 剧情 战争 警匪 犯罪 动画 奇幻 武侠 冒险 枪战 恐怖 悬疑 惊悚 经典 青春文艺 微电影 古装 历史运动 农村 儿童 网络电影
            'vod_actor',
            'vod_director',
            'vod_content',
            'vod_pic',
            'vod_year' ,
            'vod_addtime',
            'vod_filmtime',
            'vod_ispay',
            'vod_price',
            'vod_trysee',
            'vod_gold',
            'vod_length',
            'vod_multiple',
            'is_buy' => function($model) {
                return $model->is_buy;
            },
            'vod_language',
            'vod_area'
        ];
    }

    public function extraFields()
    {

        return [
             // 分组
            'groups',
             // 分组链接
            'groupLinks' => function() {

                $groups = PlayGroup::find()->where(['vod_id' => $this->vod_id])->with('links')->asArray()->all();
                $data = [];
                $data['total'] = count($groups);
                    array_walk($groups, function(&$group) {
                    array_walk($group['links'], function(&$v, $k) {
                        unset($v['season'], $v['hd_url'], $v['url'], $v['video_id']);

                        $v['_links']['self']['href'] = Url::to(["vod-links/{$v['id']}", 'access-token' => '' ], true);
                    });
                });
                $data['items'] = $groups;
                return $data;
             },

            'vodLinks' => function() {
                $items = Vodlink::find()->where(['video_id' => $this->vod_id])->select(['id', 'episode', 'plot' ])->asArray()->all();
                array_walk($items, function(&$v, $k) {
                    $v['_links']['self']['href'] = Url::to(["vod-links/{$v['id']}", 'access-token' => '' ], true);
                });

                return [
                    'total' => Vodlink::find()->where(['video_id' => $this->vod_id])->count(),
                    'items' => $items
                ];
            }
        ];
    }

    public static function getFields()
    {
        return [
            'vod_id',
            'vod_cid' ,
            'vod_name',
            'vod_ename',
            'vod_type',//喜剧 爱情 恐怖 动作 科幻 剧情 战争 警匪 犯罪 动画 奇幻 武侠 冒险 枪战 恐怖 悬疑 惊悚 经典 青春文艺 微电影 古装 历史运动 农村 儿童 网络电影
            'vod_actor',
            'vod_director',
            'vod_content',
            'vod_pic',
            'vod_year' ,
            'vod_addtime',
            'vod_filmtime',
            'vod_ispay',
            'vod_price',
            'vod_trysee',
            'vod_url',
            'vod_gold',
            'vod_length',
            'vod_multiple',
            'vod_language',
            'vod_area'
        ];
    }

    public function getVodLinks()
    {
        return $this->hasMany(Vodlink::className(), ['video_id' => 'vod_id']);
    }

    public function getGroups()
    {
        return $this->hasMany(PlayGroup::className(), ['vod_id' => 'vod_id']);
    }

    public function getGroupLinks()
    {
        return $this->hasMany(Vodlink::className(), ['group_id' => 'id'])->via('groups');
    }

    public function getLinks()
    {
       return [
           Link::REL_SELF => Url::to(['vod/view', 'id' => $this->vod_id, 'expand' => 'vodLinks'], true),
           self::LINK_GROUPLINK => Url::to(['vod/view', 'id' => $this->vod_id, 'expand' => self::LINK_GROUPLINK], true),
           self::LINK_RECOMMEND => Url::to(['recommend/view', 'id' => $this->vod_id], true)
       ];
    }

    /**
     * 关联关系
     * @return \yii\db\ActiveQuery
     */
    public function getList()
    {
        return $this->hasOne(VodList::className(), ['list_id' => 'vod_cid']);
    }


}
