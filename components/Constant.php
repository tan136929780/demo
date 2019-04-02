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
    //Active Status
    const TYPE_INACTIVE = 0;
    const TYPE_ACTIVE   = 1;

    // 用户类别
    const ROLE_TYPE_ADMIN       = 'Admin';
    const ROLE_TYPE_TYPE_NORMAL = 'Normal';
    const TYPE_NORMAL           = 1;
    const TYPE_VIP              = 2;

    const STATUS_YES = 'Y'; // 通用yes
    const STATUS_NO  = 'N'; // 通用no

    const STATUS_DISABLE = 0;
    const STATUS_ENABLE  = 1;

    const USER_ROLE_TYPE_KEY       = 'user_role_type';
    const USER_ROLE_CACHE_KEY      = 'USER_ROLE_CACHE';
    const USER_PRIVILEGE_CACHE_KEY = 'USER_PRIVILEGE_CACHE';
    const USER_MENU_CACHE_KEY      = 'USER_MENU_CACHE';

}
