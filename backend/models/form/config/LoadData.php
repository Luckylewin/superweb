<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/12/7
 * Time: 10:01
 */

namespace backend\models\form\config;

use Yii;


trait LoadData
{
    public $iniData;

    public function loadData($data)
    {
        if (!empty($data)) {
            foreach ($data as $field => $value) {
                if (property_exists($this, $field)) {
                    $this->$field = $value;
                }
            }
        }

        return $this;
    }

    public function getConfigPath()
    {
        return Yii::getAlias('@backend') . '/config/params.php';
    }

    protected function getConfig()
    {
        $configPath = $this->getConfigPath();
        if (!is_writable($configPath)) {
            throw new \Exception($configPath . ':该配置文件无写入权限');
        }

        if (!file_exists($configPath)) {
            throw new \Exception($configPath . ':该配置文件不存在');
        }

        $config = require $configPath;
        $config = is_array($config) && !empty($config)? $config : [];

        return $config;
    }

    public function saveToConfigFile()
    {
        $config = $this->getConfig();

        foreach ($this->iniData as $key => $field) {
            $config[$key] = $field;
        }

        $params = var_export($config, true);
        $configFile=<<<PHP
<?php
    return $params;
PHP;

        file_put_contents($this->getConfigPath(), $configFile);
        $data = file_get_contents($this->getConfigPath());
        file_put_contents($this->getConfigPath(), str_replace(['array (',')'],['[',']'], $data));

    }
}