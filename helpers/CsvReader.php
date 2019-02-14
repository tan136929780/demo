<?php
namespace app\helpers;
use yii\base\Object;
use yii;
/**
 * Created by PhpStorm.
 * User: baiyulong<baiyl3@lenovo.com>
 * Date: 15-7-7
 * Time: 上午10:52
 */
class CsvReader extends Object
{
    private $file;
    public $delimiter = ',';
    private $spl;
    private $error;

    /**
     * Name: setFile
     * Desc: 添加文件，设置file变量
     * User: baiyulong<baiyl3@lenovo.com>
     * Modify: wangzhonglin<wangzl10@lenovo.com>
     * Date: 2015-07-07
     * @param $file
     * @return bool
     */
    public function setFile($file)
    {
        if ($file && file_exists($file)) {
            $this->file = $file;
            $this->spl = null;
        }else{
            if(Yii::$app->aws->exist($file)){
                $ch = curl_init();
                curl_setopt($ch,CURLOPT_URL,Yii::$app->aws->getUrl($file));
                curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
                curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
                $resource=curl_exec($ch);
                curl_close($ch);
                file_put_contents(Yii::getAlias('@app') . '/runtime/'.basename($file),$resource);
                unset($resource);
                if(file_exists(Yii::getAlias('@app') . '/runtime/'.basename($file))){
                    $this->file = Yii::getAlias('@app') . '/runtime/'.basename($file);
                    $this->spl = null;
                }else{
                    $this->error = 'File Not Found';
                    return false;
                }
            }else{
                $this->error = 'File Not Found';
                return false;
            }
        }
    }

    /**
     * Name: getFile
     * Desc: 获取file
     * User: baiyulong<baiyl3@lenovo.com>
     * Date: 2015-07-07
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Name: _valid
     * Desc: 验证文件
     * User: baiyulong<baiyl3@lenovo.com>
     * Date: 2015-07-07
     * @param string $file
     * @return bool
     */
    private function _valid($file = '')
    {
        $file = $file ? $file : $this->file;
        if (!$file || !file_exists($file)) {
            return false;
        }
        if (!is_readable($file)) {
            return false;
        }
        return true;
    }

    /**
     * Name: openFile
     * Desc: 打开cvs文件
     * User: baiyulong<baiyl3@lenovo.com>
     * Date: 2015-07-07
     * @return bool
     */
    public function openFile()
    {
        if (!$this->_valid()) {
            $this->error = 'File Valid Failed';
            return false;
        }
        if ($this->spl == null) {
            $this->spl = new \SplFileObject($this->file, 'rb');
        }
        return true;
    }

    /**
     * Name: getData
     * Desc: 按制定行数读取cvs文件
     * User: baiyulong<baiyl3@lenovo.com>
     * Date: 2015-07-07
     * @param int $length
     * @param int $start
     * @return array|bool
     */
    public function getData($length = 0, $start = 0)
    {
        if (!$this->openFile()) {
            $this->error = 'Open File Failed';
            return false;
        }
        $length = $length ? $length : $this->getLines();
        $start = $start - 1;
        $start = ($start < 0) ? 0 : $start;
        $data = [];
        $this->spl->seek($start);
        while ($length-- && !$this->spl->eof()) {
            $data[] = $this->spl->fgetcsv($this->delimiter);
            $this->spl->next();
        }
        return $data;
    }

    /**
     * Name: getLines
     * Desc: 获取总行数
     * User: baiyulong<baiyl3@lenovo.com>
     * Date: 2015-07-07
     * @return bool
     */
    public function getLines()
    {
        if (!$this->openFile()) {
            return false;
        }
        $this->spl->seek(filesize($this->file));
        return $this->spl->key();
    }

    /**
     * Name: getError
     * Desc: 取得错误信息
     * User: baiyulong<baiyl3@lenovo.com>
     * Date: 2015-07-07
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }
}