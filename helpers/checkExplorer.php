<?php
/*
* @desc 全局帮助类
*/
class ExplorerCheck
{
    const SOFT_VERSION = 'V 4.0.0' ;

    /**
     * @return bool
     * @desc 低于9IE提示升级
     */
    public function check()
    {
        if (isset($_SERVER["HTTP_USER_AGENT"])) {
            #chck ie browers
            if(!strpos($_SERVER["HTTP_USER_AGENT"], "MSIE"))
                return true ;
            #check IE browers version
            if((int)substr($_SERVER["HTTP_USER_AGENT"], strpos($_SERVER["HTTP_USER_AGENT"], "MSIE") + 4, 4) >9)
                return true ;
            #onload notce page
            require(__DIR__ . '/../web/global/index.html');
            exit();
        }
    }
}
