<?php

/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/12/14
 * Time: 18:21
 */

namespace console\traits;

/**
 * 相似检查
 *
 * //返回最长公共子序列
 * echo $lcs->getLCS("hello word","hello china"); // hello
 * //返回相似度
 * echo $lcs->getSimilar("Breaking Bad S04","Breaking Bad S05"); // 0.935
 * Trait Similar
 * @package console\traits
 */
trait Similar
{
    protected $str1;
    protected $str2;

    protected $c = [];

    /**
     * 返回串一和串二的最长公共子序列
     * @param $str1
     * @param $str2
     * @param int $len1
     * @param int $len2
     * @return string
     */
    public function getLCS($str1, $str2, $len1 = 0, $len2 = 0)
    {
        $this->str1 = $str1;
        $this->str2 = $str2;
        if ($len1 == 0) $len1 = strlen($str1);
        if ($len2 == 0) $len2 = strlen($str2);
        $this->initC($len1, $len2);
        return $this->printLCS($this->c, $len1 - 1, $len2 - 1);
    }

    /**
     * 返回两个串的相似度
     * @param $str1
     * @param $str2
     * @return float|int
     */
    public function getSimilar($str1, $str2)
    {
        $len1 = strlen($str1);
        $len2 = strlen($str2);
        $len = strlen($this->getLCS($str1, $str2, $len1, $len2));
        return $len * 2 / ($len1 + $len2);
    }

    /**
     * @param $len1
     * @param $len2
     */
    public function initC($len1, $len2)
    {
        for ($i = 0; $i < $len1; $i++) $this->c[$i][0] = 0;
        for ($j = 0; $j < $len2; $j++) $this->c[0][$j] = 0;
        for ($i = 1; $i < $len1; $i++) {
            for ($j = 1; $j < $len2; $j++) {
                if ($this->str1[$i] == $this->str2[$j]) {
                    $this->c[$i][$j] = $this->c[$i - 1][$j - 1] + 1;
                } else if ($this->c[$i - 1][$j] >= $this->c[$i][$j - 1]) {
                    $this->c[$i][$j] = $this->c[$i - 1][$j];
                } else {
                    $this->c[$i][$j] = $this->c[$i][$j - 1];
                }
            }
        }
    }

    /**
     * @param $c
     * @param $i
     * @param $j
     * @return string
     */
    protected function printLCS($c, $i, $j) {
        if ($i == 0 || $j == 0) {
            if ($this->str1[$i] == $this->str2[$j]) return $this->str2[$j];
            else return "";
        }

        if ($this->str1[$i] == $this->str2[$j]) {
            return $this->printLCS($this->c, $i - 1, $j - 1).$this->str2[$j];
        } else if ($this->c[$i - 1][$j] >= $this->c[$i][$j - 1]) {
            return $this->printLCS($this->c, $i - 1, $j);
        } else {
            return $this->printLCS($this->c, $i, $j - 1);
        }
    }
}