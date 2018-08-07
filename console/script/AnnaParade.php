<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/8/6
 * Time: 10:10
 */

namespace console\script;

use backend\components\MyRedis;
use backend\models\ChannelIptv;
use backend\models\Parade;
use common\models\OttChannel;
use Yii;
use common\models\MainClass;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;

class AnnaParade extends base
{
    const XMLPATH = "/tmp/";
    protected $DIR = "/var/www/web/tmp/parades/";
    /**
     * @var mixed|string
     */
    protected $str = '{"BBB 18":"BBB2018","BBB 18 HD":"BBB2018","BBB 18 Alternativo":"BBB2018","BBB 18 FHD":"BBB2018","Globo RJ HD":"Globo RJ","Globo RJ FHD":"Globo RJ","Globo RJ Alternativo":"Globo RJ","Globo RJ Alternativo HD":"Globo RJ","Globo RJ":"Globo RJ","Globo SP":"Globo SP","Globo SP HD":"Globo SP","Globo SP FHD":"Globo SP","Globo SP Alternativo":"Globo SP","Globo NSC TV Florianopolis HD":"Globo NSC TV Florian\u00f3polis","Globo NSC TV Florianopolis":"GloboNSCTVFLORIPA","Globo MG":"Globo Minas","Globo MG HD":"Globo Minas","Globo MG FHD (Teste)":"Globo Minas","Globo MG Alternativo HD (Teste)":"Globo Minas","Globo Minas Alternativo":"Globo Minas","Globo BH":"GloboBH","Globo RPC Curitiba":"RPC Curitiba","Globo Nordeste":"Globo Nordeste","Globo Nordeste HD":"Globo Nordeste","Globo Nordeste Alternativo":"Globo Nordeste","TV Anhanguera Goiania":"TV Anhanguera Goiania","Globo Rbs":"Globo Rbs","Globo RBS Alternativo":"GloboRBSAlt","Globo Bras\u00edlia":"Globo Bras\u00edlia","Globo TV Liberal Bel\u00e9m":"TV Liberal Belem","Globo TV Amazonas":"Rede Amaz\u00f4nica Manaus","Globo Rede Amaz?nica Manaus":"GloboREDEAMAZONICAAlt","Globo TV Verdes Mares Fortaleza":"TV Verdes Mares","Globo TV Tem S. J. do Rio Preto":"TV TEM SJRP","Globo TV Tem S. J. do Rio Preto HD":"TV TEM SJRP","Globo Rio Preto - TV Tem":"GloboRioPretoTEM","Globo Tv Tem Sorocaba":"TV TEM Sorocaba","Globo Tv Tem Bauru":"TV TEM Bauru","Globo EPTV Campinas":"EPTV Campinas","Globo EPTV Campinas HD":"EPTV Campinas","Globo EPTV Ribeir?o Preto":"EPTV Ribeir\u00e3o","Globo EPTV Ribeir?o Preto HD":"EPTV Ribeir\u00e3o","Globo EPTV S?o Carlos":"EPTV S\u00e3o Carlos","Record HD":"Record","Record FHD":"Record","Record":"RecordSD","Record Alternativo":"Record","Record Alternativo HD":"Record","Sbt":"SBT","SBT HD":"SBT","SBT FHD":"SBT","SBT HD Alternativo":"SBT","SBT GO TV Serra Dourada HD":"TV Serra Dourada","Band":"Band","Band HD":"Band","Band FHD":"Band","Rede Tv":"RedeTV","Rede TV HD":"RedeTV","Rede TV FHD":"RedeTV","TV Cultura":"Cultura","Canal Brasil":"Canal Brasil","Canal Brasil HD":"Canal Brasil","TV Gazeta":"Gazeta","Futura":"Futura","TV Brasil":"TV Brasil","TV Camara":"TV C\u00e2mara","TV Senado":"TV Senado","TV Escola":"TV Escola","NBR":"NBR","Canal do Boi":"Canal do Boi","Globo News":"Globo News","Globo News HD":"GloboNews","Globo News Alternativo":"GloboNews","Globo News -Alternativo":"GloboNews","Band News":"Band News","Band News Alternativo":"Band News","Record News":"Record News","Record News Alternativo":"Record News","CNN Internacional":"CNN","SporTV":"SporTV","SporTV HD":"SporTV","SporTV FHD":"SporTV","SporTV HD Alternativo":"SporTV","SporTV Alternativo":"SporTV","SporTv 2":"SporTV 2","SporTV 2 HD":"SporTV 2","SporTV 2 HD Alternativo":"SporTV 2","SporTV 3":"SporTV 3","SporTV 3 HD":"SporTV 3","SporTV 3 HD Alternativo":"SporTV 3","Fox Sports":"FoxSports","FOX Sports HD":"FoxSports","FOX Sports FHD":"FoxSports","FOX Sports HD Alternativo":"FoxSports","Fox Sports 2":"FoxSports 2","Fox Sports 2 HD":"FoxSports 2","FOX Sports 2 HD Alternativo":"FoxSports 2","ESPN Brasil HD Alternativo":"ESPN Brasil","ESPN Brasil":"ESPN Brasil","ESPN Brasil HD":"ESPN Brasil","ESPN Brasil FHD":"ESPN Brasil","ESPN":"ESPN","ESPN HD":"ESPN","ESPN HD Alternativo":"ESPN","ESPN +":"ESPN+","ESPN + HD":"ESPN","ESPN Extra":"ESPN Extra","ESPN Extra HD":"ESPN Extra","Esporte Interativo HD":"EI Maxx","Esporte Interativo":"EI Maxx","Esporte Interativo BR HD":"Esporte Interativo","Esporte Interativo BR":"Esporte Interativo","Esporte Interativo 2":"EI Maxx 2","Esporte Interativo 2 HD":"EI Maxx 2","Combate":"Combate","Combate Dual":"Combate","Combate HD":"Combate","Combate FHD":"Combate","Combate HD Alternativo":"Combate","Band Sports":"BandSports","Band Sports HD":"BandSports","Multishow HD":"Multishow","Multishow HD Alternativo":"Multishow","Multishow":"Multishow","Viva HD":"Viva","Viva HD Alternativo":"Viva","Viva":"Viva","GNT HD":"GNT","GNT":"GNT","TruTv":"TruTV","Comedy Central":"Comedy Central","MTV":"MTV","MTV HD":"MTV","VH1 HD":"VH1","Vh1 Megahits":"VH1","Play Tv":"PlayTv","Music Box Brazil":"Music Box Brazil","Prime Box Brazil":"Prime Box","Woohoo":"Woohoo","Mais Globosat":"Mais GloboSat","Mais Globosat HD":"Mais GloboSatHD","OFF":"OFF","OFF HD":"OFF","BIS HD":"BIS","BIS":"BIS","TLC":"TLC","TLC HD":"TLC","TLC Dual":"TLC","Food Network":"Food Network","Food Network HD":"Food Network","Animal Planet":"Animal Planet","Animal Planet HD":"Animal Planet","Animal Planet Dual":"Animal PlanetDual","Discovery Channel":"Discovery Channel","Discovery Channel HD":"Discovery Channel","Discovery Turbo":"Discovery Turbo","Discovery Turbo HD":"Discovery Turbo","Discovery H&H":"Discovery Home & Health","Discovery H&H HD":"Discovery Home & Health","Discovery Civilization":"Discovery Civilization","Discovery Science":"Discovery Science","Discovery World HD":"Discovery World","Discovery Theater HD":"Discovery Theater","H2":"H2","H2 HD":"H2","H2 HD Alternativo":"H2","History Channel":"History","History HD":"History","History Dual":"History","National Geographic":"NatGeo","National Geografic HD":"NatGeo","NatGeo Wild":"NatGeo Wild","Natgeo Wild HD":"NatGeo Wild","A&E BR":"A&E","A&E HD":"A&E","A&E HD Alternativo":"A&E","A&E Dual":"A&E","ID - Investigation Discovery":"Investiga\u00e7\u00e3o Discovery","ID - Investiga??o Discovery HD":"Investiga\u00e7\u00e3o DiscoveryHD","E! BR":"E!","E! HD":"E!","Arte 1":"Arte 1","Curta!":"Curta!","NHK":"NHK World Premium","Rede Brasil":"Rede Brasil","Lifetime":"Lifetime","Lifetime HD":"Lifetime","Fish Tv":"Fish TV","RIT":"RIT","RBI TV":"RBI","Novo tempo":"Novo Tempo","Tv Can??o Nova":"Can\u00e7\u00e3o Nova","Rede Fam\u00edlia":"Rede Fam\u00edlia","TV Aparecida":"TV Aparecida","Rede Vida":"Rede Vida","Rede Gospel":"Rede Gospel","Rede 21":"Rede 21","Boa Vontade TV":"Boa Vontade TV","TV Evangelizar":"TV Evangelizar","Telecine Premium":"Telecine Premium","Telecine Premium HD":"TC Premium","Telecine Premium FHD":"TC Premium","Telecine Premium HD Dual":"TC Premium","Telecine Premium Dual":"Telecine Premium","Telecine Pipoca":"Telecine Pipoca","Telecine Pipoca HD":"TC Pipoca","Telecine Pipoca FHD":"TC Pipoca","Telecine Pipoca Dual":"TC Pipoca","Telecine Pipoca HD Dual":"Telecine Pipoca","Telecine Action":"Telecine Action","Telecine Action HD":"TC Action","Telecine Action FHD":"TC Action","Telecine Action Dual":"TC Action","Telecine Action HD Dual":"TC Action","Telecine Touch":"Telecine Touch","Telecine Touch Dual":"Telecine Touch","Telecine Touch HD":"TC Touch","Telecine Touch FHD":"Telecine Touch","Telecine Touch HD Dual":"TC Touch","Telecine Fun":"Telecine Fun","Telecine Fun HD":"TC Fun","Telecine Fun Dual":"TC Fun","Telecine Fun HD Dual":"TC Fun","Telecine Fun FHD":"TC FunFHD","Telecine Cult":"Telecine Cult","Telecine Cult Dual":"Telecine Cult","Telecine Cult HD":"Telecine Cult","Fox":"Fox","FOX HD":"Fox","FOX FHD":"Fox","FOX HD Alternativo":"Fox","FOX Dual":"Fox","Fox Premium 1":"Fox Premium 1","Fox Premium 2":"Fox Premium 2","FOX LIFE HD":"FoxLife","Fox Life":"FoxLife","FX":"FX","FX Dual":"FX","FX HD":"FX","HBO":"HBO","HBO HD":"HBO","HBO Dual":"HBO","HBO HD Alternativo":"HBO","HBO 2":"HBO 2","HBO 2 HD":"HBO 2","HBO 2 HD Alternativo":"HBO 2","HBO 2 Dual":"HBO 2","HBO Signature Dual":"HBO Signature","HBO Signature HD":"HBO Signature","HBO Signature":"HBO Signature","HBO Signature HD Alternativo":"HBO Signature","HBO Family":"HBO Family","HBO Family Dual":"HBO Family","HBO Family HD":"HBO Family","HBO Family HD Alternativo":"HBO Family","HBO Plus":"HBO Plus","HBO Plus Dual":"HBO Plus","HBO Plus HD":"HBO Plus","HBO Plus HD Alternativo":"HBO Plus","HBO Plus e":"HBO PlusE","HBO Plus E Dual":"HBO PlusE","Max Prime":"MaxPrime","Max Prime HD":"MaxPrime","MAX Prime e":"MaxPrimeE","Max Prime E -LEG":"MaxPrime","Max UP":"Max UP","MAX UP HD":"Max UP","Max":"Max","TNT":"TNT","TNT HD":"TNT","TNT HD Alternativo":"TNT","MegaPix HD":"Megapix","MegaPix":"MegaPix","MegaPix HD Alternativo":"Megapix","MegaPix Dual":"Megapix","Space HD":"SpaceHD","Space HD Alternativo":"SpaceHD","Space Cinemax":"Space Cinemax","Cinemax HD":"Cinemax","Cinemax Dual":"Cinemax","Paramount HD":"Paramount","Paramount HD Alternativo":"Paramount","Paramount":"Paramount","AXN":"AXN","AXN HD":"AXN","AXN HD Alternativo":"AXN","AXN Dual":"AXN","Universal Channel HD":"Universal Channel","Universal Channel":"Universal Channel","Universal Channel HD Alternativo":"Universal Channel","Warner Channel":"Warner Channel","Warner Channel HD":"Warner Channel","Warner Channel HD Alternativo":"Warner Channel","Warner Channel Dual":"Warner Channel","Sony":"Sony","Sony HD":"Sony","Sony HD Alternativo":"Sony","TNT S\u00e9ries":"TNT Series","TNT S\u00e9ries HD":"TNT Series","TNT S\u00e9ries HD -Alternativo":"TNT Series","AMC Dual":"AMC","AMC":"AMC","SundanceTV":"Sundance","SundanceTV HD":"Sundance","Syfy":"Syfy","SyFy HD":"Syfy","Studio Universal":"Studio Universal","Studio Universal Dual":"Studio Universal","Studio Universal HD":"Studio Universal","TCM":"TCM","TCM Dual":"TCM","I-Sat":"I.Sat","Discovery Kids":"Discovery Kids","Discovery Kids HD":"Discovery Kids","Disney Channel":"Disney Channel","Disney Channel HD":"Disney Channel","NatGeo Kids":"NatGeoKids","NatGeo Kids HD":"NatGeoKids","Cartoon Network":"Cartoon Network","Cartoon Network HD":"Cartoon Network","Cartoon Network Dual":"Cartoon Network","Gloobinho":"Gloobinho","Gloobinho HD":"Gloobinho","Gloob":"Gloob","Gloob HD":"Gloob","Gloob Alternativo":"GloobAlt","Nickelodeon":"Nick","Nickelodeon HD":"Nick","Boomerang":"Boomerang","Boomerang HD":"Boomerang","Disney Jr":"Disney Junior","Disney Jr HD":"Disney Junior","Zoomoo":"Zoomoo","Baby-TV":"BabyTV","Disney XD":"Disney XD","TBS":"TBS","TBS HD":"TBS","TBS Dual":"TBS","TBS HD Alternativo":"TBS","Nick Jr":"Nick Jr.","TV R\u00e1 Tim Bum":"TV R\u00e1-Tim-Bum","Tooncast":"Tooncast","PFC":"PremiereBRSD","PFC Alternativo":"PremiereBRSD","Premiere Clubes":"Premiere Clubes","Premiere Clubes HD":"Premiere Clubes","Premiere Clubes FHD":"Premiere Clubes","Premiere Clubes HD Alternativo":"Premiere Clubes","Premiere Clubes Alternativo":"Premiere Clubes","Premiere Clubes -Alternativo":"Premiere Clubes","Premiere 2":"Premiere 2","Premiere 2 HD":"Premiere 2","Premiere 3":"Premiere 3","Premiere 3 HD":"Premiere 3","Premiere 4":"Premiere 4","Premiere 4 HD":"Premiere 4","Premiere 5":"Premiere 5","Premiere 5 HD":"Premiere 5","Premiere 6":"Premiere 6","Premiere 6 HD":"Premiere 6","Premiere 7":"Premiere 7","Premiere 7 HD":"Premiere 7","Premiere 8":"Premiere 8","Premiere 9":"Premiere 9","SexPrive":"SexPrive","SexyHot":"SexyHots","Venus":"Venus","Playboy":"PlayboyTV","Sextreme":"Sextreme","Big":"Big"}';
    /**
     * @var MyRedis
     */
    private $redis;

    private $debug = true;

    public function __construct($controller)
    {
        parent::__construct($controller);

        $this->redis = MyRedis::init(MyRedis::REDIS_PARADE_CONTENT);
        $classData = MainClass::find()
                                ->alias('a')
                                ->joinWith('subChannel')
                                ->where(['a.name' => 'br'])
                                ->one();

        $items = [];
        $items[] = ArrayHelper::map($classData->subChannel, 'name', 'alias_name');

        if (!empty($items)) {
            $this->str = $items;
        } else {
            $this->str = json_decode($this->str, true);
        }

    }

    /**
     * 获取巴西节目预告
     */
    public function dealParade()
    {
        $source = [
            'http://infserver.com/guide.xml',
            'http://tinyurl.com/guiainfinity',
            'http://epg.starbr.in/starbr.xml',
            'http://infurl.ml/epg',
            'http://kingiptv.ml/epg'
        ];

        foreach ($source as $key => $url) {
            $this->_readXml($url, $key);
        }
        return true;
    }



    private function _readXml($url, $key)
    {
        if (!$this->_downloadXml($url, $key)) {
            return false;
        }

        $filename = $this->getFileName($key);

        $reader = new \XMLReader();
        $reader->open($filename);

        echo "正在读取文件" ,PHP_EOL;

        $total = 0;
        try {
          while ($reader->read()) {
            switch ($reader->nodeType) {
              case (\XMLREADER::ELEMENT):
                if ($reader->localName == "programme") {
                    $start = $reader->getAttribute("start");
                    $stop = $reader->getAttribute("stop");
                    $channel = $reader->getAttribute("channel");

                    if($this->debug) {

                        $this->stdout("start:$start,stop:$stop" . PHP_EOL, Console::FG_GREY);
                        $this->stdout("节目:" . $channel .PHP_EOL);

                        $this->stdout("开始:" .
                        date('Y-m-d ',strtotime(substr($start,0,14))).
                        date('H:i:s',strtotime(substr($start,0,14))).
                        PHP_EOL);

                        $this->stdout("结束:" .
                        date('Y-m-d ',strtotime(substr($stop,0,14))).
                        date('H:i:s',strtotime(substr($stop,0,14))).
                        PHP_EOL);
                    }

                    $microStart = strtotime(substr($start,0,14));
                    $microStop = strtotime(substr($stop,0,14));
                    $yesterday = strtotime('yesterday');

                    if ($start) {
                        while ($reader->read()) {
                            if ($reader->nodeType == \XMLREADER::ELEMENT) {

                                if ($reader->localName == "title") {
                                    $reader->read();
                                    $title = $reader->value;
                                    if ($this->debug) $this->stdout('内容:'.$title.PHP_EOL.PHP_EOL);
                                    break;
                                }

                                if ($reader->localName == "desc") {
                                    $desc = $reader->read();
                                    if ($this->debug) $this->stdout('描述:'.$reader->value .PHP_EOL);
                                    break;
                                }

                            }
                        }
                    }

              $between = floor(($microStop - $microStart) / 86400);
              $_key = array_search($channel, $this->str);
              $alisa =  $_key? $_key : $channel;

              $channelData = OttChannel::findOne(['name' => $alisa]);

               //如果大于1天
               if ($between > 1 && isset($title)) {
                  $this->_dealRepeatDay($microStart,$microStop,$yesterday, $channelData);
               }

               if ($channelData && isset($title) && $microStart >= $yesterday) {
                  $this->_addParade($channelData, $title, $microStart, $yesterday);
               }

               if ($this->debug){
                  $this->stdout($total .PHP_EOL .PHP_EOL);
               }

                $total++;

             }
               break;
           }
         }

        } catch (\Exception $e) {
            $this->stdout("读取错误" .PHP_EOL, Console::FG_RED);
        }

            $reader->close();
        }

    private function _addParade($channelData, $title, $microStart, $yesterday)
    {
        $parade['channel_id'] = $channelData->id;
        $parade['channel_name'] = $channelData->alias_name;
        $parade['parade_date'] = date('Y-m-d',$microStart);
        $parade['upload_date'] = date('Y-m-d',time());


        $parade['parade_data'] = [
            'parade_name' => $title,
            'parade_time' => date('H:i',$microStart)];

        $isExist = Parade::find()
            ->where(
                [
                    'channel_name' => $channelData['alias_name'],
                    'parade_date' => $parade['parade_date']])
            ->limit(1)
            ->one();

        if (!$isExist &&!empty($channelData)) {
            $parade['parade_data'] = json_encode(array($parade['parade_data']));
            // 新增 parade
            Yii::$app->db->createCommand()->insert('iptv_parade', $parade)->execute();
        } else {
            $data = json_decode($isExist['parade_data'],true);

            if (!empty($data)) {
                $flag = false;
                foreach ($data as $v) {
                    if ($v['parade_time'] == date('H:i',$microStart)) {
                        $flag = true;
                    }
                }
                if ($flag == false) {
                    array_push($data,$parade['parade_data']);
                    $parade['parade_data'] = $data;
                    // 更新
                    Yii::$app->db->createCommand()->update('iptv_parade', ['parade_data'=>json_encode($parade['parade_data'])], ['id' => $isExist['id']])->execute();

                }
            }
        }
    }


    private function _dealRepeatDay($microStart, $microStop, $yesterday, $channelData)
    {
        $parade = [];

        if (strtotime(date('Y-m-d',$microStart)) <= $yesterday
            &&
            $yesterday < strtotime(date('Y-m-d',$microStop))
           ) {
            //计算昨天到结束 一共有多少天
            $between = floor(($microStop -  $yesterday) / 86400);
            if ($channelData && isset($title)) {
              for ($i=0; $i<$between; $i++) {
                 $parade['channel_id'] = $channelData->id;
                  $parade['parade_date'] = date('Y-m-d',$yesterday + 86400 * $i);
                    $parade['channel_name'] = $channelData->alias_name;
                    $parade['upload_date'] = date('Y-m-d',time());
                    $parade['parade_data'] = json_encode(['parade_name' => $title,'parade_time'=>'00:00']);
                    $isExist = Parade::findOne(['channel_name' => $channelData->alias_name, 'parade_date' => $parade['parade_date']]);

                    if (is_null($isExist)) {
                        Yii::$app->db->createCommand()->insert('iptv_parade', $parade)->execute();
                    }
                }
            }
        }

        return true;
    }

    private function _downloadXml($url, $key)
    {
        $filename = $this->getFileName($key);

        if (file_exists($filename)) {
            return true;
        }

        try {
            $this->stdout("正在下载文件 {$filename} " . PHP_EOL);
            file_put_contents($filename, file_get_contents($url));
            $this->stdout("文件{$filename} 下载完毕" . PHP_EOL);
            return true;
        } catch (\Exception $e) {
            $this->stdout("文件{$filename} 下载失败" . PHP_EOL, Console::FG_RED);
            return false;
        }

    }

    public function getFileName($key)
    {
        return self::XMLPATH . $key . "-guide.xml";
    }
}