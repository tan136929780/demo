<?php
/**
 * Created by PhpStorm.
 * User: tanxianchen
 * Date: 2019-02-20
 * Time: 14:18
 */
namespace app\components;

/**
 * Class Constant
 * @package app\components
 * @Desc 该类主要定义一些常量,方便以后常量的管理,全部以const定义,没有function
 * @ -----------------------------
 * @ const MY_CONST  //注释
 * @ -----------------------------
 */
class Constant
{
    const USER_TIME_ZONE_KEY = 'USER_TIME_ZONE';
    const CACHE_KEY_SDFCODE = 'SDFCODE_CACHE_LIST';
    const CACHE_KEY_COUNTRY_WARRANTY = 'COUNTRY_WARRANTY_CACHE_LIST';

    //Active Status
    const TYPE_INACTIVE = 0;
    const TYPE_ACTIVE = 1;

    const STATUS_ENABLE = self::TYPE_ACTIVE; // ./models/BaseModel.php:19
    const STATUS_DISABLE = self::TYPE_INACTIVE; // ./models/BaseModel.php:18

    const STATUS_YES = 'Y'; // 通用yes
    const STATUS_NO = 'N'; // 通用no

}
