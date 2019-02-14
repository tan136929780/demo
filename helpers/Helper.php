<?php
/**
 * Created by PhpStorm.
 * User: tanxianchen
 * Date: 2019-02-20
 * Time: 下午4:25
 */
namespace app\helpers;

use app\components\Constant;
use Yii;
use c006\alerts\Alerts;
use yii\web\Response;

class Helper
{
    /**
     * Name: date2timestamp
     * Desc: 将用户选择的日期转换成时间戳,考虑用户所在的时区
     * User: tanxianchen
     * Date: 2019-02-20
     * @param string $date
     * @param string $timezone
     * @return int
     */
    public static function date2timestamp($date = '', $timezone = '')
    {
        if (empty($date)) {
            return 0;
        }
        $date = date('Y-m-d H:i:s', strtotime($date));
        $formatter = Yii::$app->getFormatter();

        $formatter->defaultTimeZone = empty($timezone) ? 'UTC' : $timezone;
        //Get User Time Zone
        $userTimeZone = Yii::$app->session->get(Constant::USER_TIME_ZONE_KEY);
        if (!Yii::$app->user->getIsGuest() && empty($timezone) && $userTimeZone) {
            $formatter->defaultTimeZone = $userTimeZone;
        }

        return intval($formatter->asTimestamp($date));
    }

    /**
     * Name: timestamp2date
     * Desc: 将时间戳转换成用户所在的日期
     * User: tanxianchen
     * Date: 2019-02-20
     * @param  $time
     * @param string $format
     * @param string $timezone
     * @return bool|string
     */
    public static function timestamp2date($time = 0, $format = 'Y-m-d H:i:s', $timezone = '')
    {
        $formatter = Yii::$app->getFormatter();
        $formatter->timeZone = empty($timezone) ? 'UTC' : $timezone;
        //Get User Time Zone
        if (!Yii::$app->user->getIsGuest() && empty($timezone)) {
            $formatter->timeZone = Yii::$app->getSession()->get(Constant::USER_TIME_ZONE_KEY);
        }
        return !empty($time) ? $formatter->asDate($time, "php:{$format}") : '';
    }

    /**
     * Name: Alert
     * Desc: 设置闪存信息
     * User: tanxianchen
     * Date: 2019-02-20
     * @param string $msg
     * @param string $type info danger success
     * @param int $cd
     */
    public static function Alert($msg = 'Nothing', $type = 'info', $cd = 3)
    {
        Alerts::setMessage("<strong>$msg</strong>");
        Alerts::setAlertType("alert-$type");
        Alerts::setCountdown($cd);
    }

    /**
     * Name: getColorForHome
     * Desc: Home页面根据数字大小获取颜色
     * User: tanxianchen
     * Date: 2019-02-20
     * @param int $num int row 0为其它 1为第一个
     * @return string
     */
    public static function getColorForHome($num, $row = 0)
    {
        $tmp_num = (int)$num;
        // 小于1 green 1-9 yellow 大于9 red
        $green = 'rgb(24, 204, 108)';
        $yellow = 'rgb(255, 167, 25)';
        $red = 'rgb(255, 90, 77)';
        $green_class = 'label label-success';
        $yellow_class = 'label label-warning';
        $red_class = 'label label-danger';
        if ($tmp_num < 1) {
            $ret_color = $green;
            $ret_class = $green_class;
        } else if ($tmp_num > 9) {
            $ret_color = $red;
            $ret_class = $red_class;
        } else {
            $ret_color = $yellow;
            $ret_class = $yellow_class;
        }
        if ($row == 0) {
            return $ret_color;
        } else {
            return $ret_class;
        }
    }
    
    /**
     * Name: arraySearchKey
     * Desc: 多维数组搜索某个键,并返回值
     * User: tanxianchen
     * Date: 2019-02-20
     * @param $needle
     * @param $haystack
     * @return array
     */
    public static function arraySearchKey($needle, $haystack)
    {
        global $found;
        foreach ($haystack as $key => $value) {
            if ($key === $needle) {
                $found[] = $value;
            }
            if (is_array($value)) {
                self::arraySearchKey($needle, $value);
            }
        }
        return $found;
    }

    /**
     * Name: getExportFileName
     * Desc: 获取导出的文件名,格式类似name_yyMMdd_hhmmss_random(5).csv
     * User: tanxianchen
     * Date: 2019-02-20
     * Modifier:
     * ModifiedDate:
     * @param $prefix
     * @param string $fileType
     * @return string
     */
    public static function getExportFileName($prefix, $fileType = 'csv')
    {
        return $prefix . date('_Ymd_His_') . rand(10000, 99999) . '.' . $fileType;
    }

    /**
     * Name: renderJson
     * Desc: Return Json Data
     * User: tanxianchen
     * Date: 2019-02-20
     * @param array $data
     */
    public static function renderJson($data = [])
    {
        $response = Yii::$app->getResponse();
        $response->format = Response::FORMAT_JSON;
        $response->data = $data;
        $response->send();
        exit;
    }

    /**
     * Name: getTimeFromMinutes
     * Desc: 通过传入的分钟数获取时间
     * User: tanxianchen
     * Date: 2019-02-20
     * @param string $minutes
     * @return string 时间
     */
    public static function getTimeFromMinutes($minutes)
    {
        $hours = floor($minutes / 60);
        $min = ($minutes % 60);
        $hours = $hours >= 10 ? $hours : '0' . $hours;
        $min = $min >= 10 ? $min : '0' . $min;
        $time = $hours . ':'. $min;
        return $time;
    }

    /**
     * @param string $date
     * @param string $timezone
     * @return int
     *
     * 之前的date2timestamp针对"+1 day", "last month"等在传入timezone的情况有问题.
     *
     */
    public static function stringDate2timestamp($date = '', $timezone = '')
    {
        if (empty($date)) {
            return 0;
        }

        $formatter = Yii::$app->getFormatter();

        $formatter->defaultTimeZone = empty($timezone) ? 'UTC' : $timezone;

        return intval($formatter->asTimestamp($date));
    }
}
