<?php
namespace app\helpers;
/**
 * 数据同步
 * User: liuyy23<liuyy23@lenovo.com>
 * Date: 2016-01-13
 */
class DataSyncController
{   
    
    /**
    * @desc 入口
    */
    public static function main()
    {
        $Config = self::config();
	
	self::ClearDB($Config);

        self::createfolder($Config);
        
        self::mysql($Config);
        
        self::zip($Config);

        #self::ClearDB($Config);
    }

    /**
    * @desc 公用参数
    */
    public static function config()
    {
        return [
            /*'hosts'   => '127.0.0.1',
            'usename' => 'root',
            'passwd'  => '123456',*/
            'hosts'   => 'mds-prd-now.cya82dbsnmug.ap-southeast-1.rds.amazonaws.com',
            'usename' => 'a_mds',
            'passwd'  => 'abcd-1234',
            'dbname'  => 'mds',
            'folder'  => dirname(dirname(__FILE__))."/web/assets/mysql/",
            'dates'   => date("Ymd").self::rule(),
            'keys'    => md5('127.0.0.1'),
        ];
    }

    /**
    *同步频率设定*
    */
    private static function rule()
    {
        $hour = date("H") ;
        //自行修改频率
        if($hour < 20)      return 1 ;
        elseif($hour > 20)  return 2 ;
    }

    /**
    * @desc 创建文件夹
    */
    public static function createfolder($config)
    {
        if (!is_dir($config['folder'])) mkdir($config['folder'], 0777);
    }

    /**
    * @desc 数据打包 
    */
    private static function mysql($config){
        
        $mysql  = "mysqldump -h".$config['hosts'] ;
        $mysql .= " -u".$config['usename'] ;
        $mysql .= " -p".$config['passwd'] ;
        $mysql .= " ".$config['dbname'] ;
        $mysql .= " >".$config['folder'] ;
        $mysql .= "mds.sql" ;
        #echo $mysql."\n" ;exit;
        self::order($mysql);
    }

    /**
    * @desc 数据压缩加密
    */
    private static function zip($config)
    {
        $order  = "zip -jP ".$config['keys'];
        $order .= " ".$config['folder'].$config['dates'].".zip" ;
        $order .= " ".$config['folder']."mds.sql" ;
        self::order($order);
    }

    /**
    * @desc 清楚数据SQL 清理一周以前的数据
    */
    public static function ClearDB($config)
    {
        $folder = $config['folder'] ;
        
        if(is_dir($folder)){

            if(count(scandir($folder)) > 10){

                self::order("rm -rf ".$folder);
            }

        }else{
            self::order("rm -rf ".$folder."mds.sql");
        }
    }

    /**
    * @desc 命令方法
    */
    private static function order($order)
    {
        return exec($order) ;
    }
}

date_default_timezone_set('Asia/Shanghai'); 
DataSyncController::main();
