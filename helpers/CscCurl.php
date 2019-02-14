<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-08-28
 * Time: 10:02
 */

namespace app\helpers;
use app\models\StationUser;
use Yii;
use app\libs\Curl;


class CscCurl
{
    private $login_url;
    private $url;
    private $token;
    const CATEGORY_EXECUTE = 'CSC＿EXECUTE';
    const Subscriber_id = 'CSC00001';
    const reported_system ='MDS';
    const csc_key = 'csc_token';

    function  __construct()
    {
        $this->url = Yii::$app->params['CSC_URL_API'];
        $this->login_url = Yii::$app->params['CSC_URL_LOGIN'];
        /*获取token*/
        $redis = Yii::$app->redis;
        if ($val = $redis->get(self::csc_key)) {
            $this->token  = $val;
        } else {
            $result = $this->login(); //如果没有找到， 就是过期，调用 登陆程序，程序获取 token
            if($result){
                $this->token = $result['token_type'] . ' ' . $result['access_token'];
                $redis->set(self::csc_key, $this->token);
                $redis->expire(self::csc_key, $result['expires_in']); //过期时间一天
            }else{
                $this->setLog("login  error");
            }
        }
    }

    function  login(){
        $auth = 'Basic ' . base64_encode(Yii::$app->params['CSC_KEY'] . ':' . Yii::$app->params['CSC_SECRET']);
        $data['grant_type'] = 'client_credentials';
        $result = $this->_execLogin($this->login_url,$data,$auth);
        return $result;
    }

    private function _execLogin($url, $data, $auth) {
        $curl = new Curl();
        $curl->setHeader('Authorization', $auth);
        $curl->setHeader('Content-Type', 'application/x-www-form-urlencoded');
        $curl->setOpt(CURLOPT_TIMEOUT, 30);
        // todo chendan
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        $curl->setOpt(CURLOPT_SSL_VERIFYHOST, false);
        $curl->post($url,$data);
        if( $curl->response){
            $result = json_decode($curl->response,true);
            if(isset($result['access_token'])){
                return $result;
            }
        }
        return false;
    }

    /*
     *
     * 传个数组，四个子项
     * user_id
     * case_title
     * case_detail
     * display_name
     * attechment
     * */
    function  ticket_add ($d){
        $login_url = $this->url."ticket_add";
        $data['subscriber_id'] = self::Subscriber_id ;
        $data['reported_system'] =self::reported_system;
        $data['user_id'] =$d['user_id'];
        $data['case_title'] = $d['case_title'] ;
        $data['case_detail'] =$d['case_detail'] ;
        $data['case_type'] ='0' ;
        $data['display_name'] =$d['display_name'] ;
        if(!empty($d['attachment']))$data['attachment'] =$d['attachment'] ;
        $this->setTransactionToLog("method:".__METHOD__);
        /*
         * [ { "subscriber_id": "CSC00001", "reported_system": "ITSM", "user_id": "shiyj3", "case_title": "开单测试", "case_detail": "我是描述", "case_type": "1", "display_name": "杨大卫", "email_notify": "1", "email_address": "email_address", "phone": "", "attachment": { "name": "附件名称.txt", "content": "ACSADASXAXASDASD==" }, "servicecategory_1": "", "servicecategory_2": "", "servicecategory_3": "", "support_organization": "", "support_group": "" } ]
         * 通过测试
         *能够返回值
         * */
        $res = $this->_exec($login_url,$data,  true,false, true);
        return $res;
        /*
         * 返回值 ：
         * [{"statusCode":"00","status":true,"content":"000000000000486","allCount":0,"currentCount":0,"other":null}]
         *
         * */
    }


    /*
     * my status   0
     * ,1
     * ,2
     * */
    function  my_ticket_list ($d){
        switch ($d['my_status']) {
            case 0:
                $d['status']=['1','2','3', '7'];
                break;
            case 1:
                $d['status'] = ['4'];
                break;
            case 2:
                $d['status'] = [];
                $d['ready_for_survey'] =0;
                break;
            case 3:
                $d['status'] = ['5','6'];
                break;
            default:;
        }
        return  $this->ticket_list($d);
    }

    function  ticket_list ($d){
        $login_url = $this->url."ticket_list";
        $data['reported_system'] =self::reported_system ;
        $data['user_id']  = $d['user_id'] ;
        $data['status'] =isset($d['status'])?$d['status']:'' ;
        $data['title'] = empty($d['title'])? '': $d['title'];
        $data['submit_date_start'] =!empty($d['submit_date_start'])?$d['submit_date_start']:'' ;
        $data['submit_date_end'] =!empty($d['submit_date_end'])?$d['submit_date_end']:'' ;
        $data['page_num'] =isset($d['page_num'])? $d['page_num']:'1';
        $data['page_size'] =isset($d['page_size'])? $d['page_size']:'20' ;
        $data['columnList'] =array();
        if(isset($d['ready_for_survey'] ))   $data['ready_for_survey'] =$d['ready_for_survey'];
        /*
        [{ "reported_system": "LenovoCSC", "user_id": "shiyj3", "status": "", "submit_date_start": "", "submit_date_end": "", "page_num": 1, "page_size": 20, "columnList": ["case_id", "status"] }]
              * 通过测试
         *
         * [{ "reported_system": "LenovoCSC", "user_id": "shiyj3", "status":6, "submit_date_start": "", "submit_date_end": "", "page_num": 1, "page_size": 20, "columnList": ["case_id", "status"] }]
         *
         *
         *能够返回值
         * */
        $this->setTransactionToLog("method:".__METHOD__);
        return $this->_exec($login_url,$data);
        /*
         *
        *
        * [{"statusCode":"00","status":true,"content":[{"case_id":"180820000002","status":{"code":1,"name":"assigned"}},{"case_id":"180820000008","status":{"code":5,"name":"closed"}},{"case_id":"180820000010","status":{"code":5,"name":"closed"}},{"case_id":"180820000012","status":{"code":1,"name":"assigned"}},{"case_id":"180821000014","status":{"code":1,"name":"assigned"}},{"case_id":"180821000015","status":{"code":5,"name":"closed"}},{"case_id":"180821000017","status":{"code":1,"name":"assigned"}},{"case_id":"180822000004","status":{"code":1,"name":"assigned"}},{"case_id":"180822000011","status":{"code":1,"name":"assigned"}},{"case_id":"180822000022","status":{"code":5,"name":"closed"}},{"case_id":"180822000023","status":{"code":5,"name":"closed"}},{"case_id":"180823000005","status":{"code":5,"name":"closed"}},{"case_id":"180823000006","status":{"code":5,"name":"closed"}},{"case_id":"180823000009","status":{"code":5,"name":"closed"}},{"case_id":"180823000010","status":{"code":5,"name":"closed"}},{"case_id":"180823000011","status":{"code":5,"name":"closed"}},{"case_id":"180824000001","status":{"code":1,"name":"assigned"}},{"case_id":"180824000004","status":{"code":1,"name":"assigned"}},{"case_id":"180824000005","status":{"code":1,"name":"assigned"}},{"case_id":"180824000006","status":{"code":1,"name":"assigned"}}],"allCount":26,"currentCount":20,"other":null}]
        *
        *
        * */

    }


    function  total_count($d){
        $url = $this->url."ticket_list";
        $new_data = array();
        $data['reported_system'] =  self::reported_system;
        $data['user_id'] =$d['user_id'] ;
        $data['page_num'] =1;
        $data['page_size'] =1;
        $data['columnList'] =['case_id'];
        $data['submit_date_start'] ='' ;
        $data['submit_date_end'] ='' ;
        $data['status'] =['1','2','3', '7'] ;
        $new_data[] = $data;
        $data['status'] =['4'];
        $new_data[] = $data;
        $data['status'] =[];
        $data['ready_for_survey'] =0;
        $new_data[] = $data;
        $data['status'] =['5','6'];
        unset($data['ready_for_survey'] );
        $new_data[] = $data;
        $this->setTransactionToLog("method:".__METHOD__);
        return  $this->_exec($url,$new_data,true,true);
    }

    /**


     */
    function  worklog_list($d){
        $url = $this->url."worklog_list";
        $data['case_id'] =$d['case_id'] ;
        $data['page_num'] =isset($d['page_num'])? $d['page_num']:'1';
        $data['page_size'] =isset($d['page_size'])? $d['page_size']:'40' ;
        $data['columnList'] =[] ;
        $this->setTransactionToLog("method:".__METHOD__);
        return  $this->_exec($url,$data);
    }
    /*
     *
     * case_id,
     * worklog_note
     *
     * */
    function  worklog_add($d){
        $url = $this->url."worklog_add";
        $data['case_id'] =$d['case_id'] ;
        $data['worklog_note'] =$d['worklog_note'] ;
        $data['submitter'] =$d['submitter'] ;
        if( !empty($d['attach']) ) $data['attach']  =$d['attach'] ;
        $this->setTransactionToLog("method:".__METHOD__);
        $result =  $this->_exec($url,$data, true, false, true);
    }

    /*
     *
     * $data['case_id']
     * $data['action']
     * $data['comfirm_closed_reason']
     * */
    function  ticket_close($d){
        $url = $this->url."ticket_close";
        $data['case_id'] =$d['case_id'] ;
        $data['action'] =$d['action']  ;
        $data['comfirm_closed_reason'] =$d['comfirm_closed_reason'] ;
        $data['submitter'] =$d['submitter']  ;
        $this->setTransactionToLog("method:".__METHOD__);
        $result =  $this->_exec($url,$data);
    }


    /*
 *
 * $data['case_id']
 * $data['action']
 * $data['comfirm_closed_reason']
 * */
    function  survey_add($d){
        $url = $this->url."survey_add";
        $data['case_id'] =$d['case_id'] ;
        $data['satisfactoriness'] =$d['satisfactoriness']  ;
        $data['satisfied_reason'] =$d['satisfied_reason'] ;
        $data['submitter'] =$d['submitter'] ;
        $this->setTransactionToLog("method:".__METHOD__);
        $result =  $this->_exec($url,$data);
    }



    function  attach($d){
        $url = $this->url."attach";
        $data['attatch_id'] =$d['attatch_id'] ;
        $this->setTransactionToLog("method:".__METHOD__);
        return $result = $this->_exec($url,$data);
    }


    /*
     * case_id
     * */
    function  ticket_detail($d){
        $login_url = $this->url."ticket_detail";
        $data['case_id'] =$d['case_id'] ;
        $this->setTransactionToLog("method:".__METHOD__);
        /*
         *
         *能够返回值
         * */
        return   $result =  $this->_exec($login_url,$data);

    }

    /**
     * Name: getUpdateItemNum
     * Desc: get my case update number by category
     * User: CHENDAN5
     * Date: 18-12-26
     * @param $user_id
     * @return mixed
     */
    public function getUpdateItemNum($user_id)
    {
        $url = $this->url."ticket_list";
        $content = [
            'reported_system' => self::reported_system,
            'user_id' => $user_id,
            'status' => [],
            'ready_for_survey' => '',
            'ready_for_close' => '',
            'update_by_support' => 1,
            'submit_date_start' => '',
            'submit_date_end' => '',
            'customer' => '',
            'title' => '',
            'page_num' => 1,
            'page_size' => 1,
            'columnList' => ['case_id'],
        ];
        $data = [$content, $content, $content];
        $data[0]['status'] =['1','2','3', '7'] ;
        $data[1]['status'] =['4'];
        $data[2]['ready_for_survey'] =0;
        return $this->_exec($url, $data, true, true);
    }

    /**
     * Name: clearUpdateFlag
     * Desc:  csc interface to clear update flag
     * User: CHENDAN5
     * Date: 18-12-26
     * @param $case_id
     * @param $user_id
     * @return mixed
     */
    public function clearUpdateFlag($case_id, $user_id)
    {
        $url = $this->url . 'push_update';
        return $this->_exec($url, ['case_id' => $case_id, 'submitter' => $user_id]);
    }

    function _exec($url,$post_data,$auth=true,$total_count= false, $alert = false){
        if(!$total_count) {
            $data[]= $post_data;
        }else{
            $data = $post_data;
        }
        $json_data = json_encode($data);
        $this->setTransactionToLog("request_json_data:".$json_data);
        $curl = new Curl();
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        $curl->setOpt(CURLOPT_SSL_VERIFYHOST, false);
        $curl->setHeader('Content-Type', 'application/json');
        if($auth){
            $curl->setHeader('Authorization', $this->token);
        }
        $curl->setOpt(CURLOPT_TIMEOUT, 30);;
        $curl->post($url,$json_data);
        if ($curl->http_status_code == '401') {
            $this->clearToken();
            $this->__construct();// 重新初始化
        }
        if($curl->response){
            $result = json_decode($curl->response,true);
            if($total_count){
                return $result;
            }
            return $result[0];
        }else {
            $alert && Helper::Alert("Failed, Please upload again!", 'danger');
            $this->setLog("no response");
            return [];
        }
    }


    function  clearToken(){
        /*获取token*/
        $redis = Yii::$app->redis;
        $redis->del(self::csc_key);
        $this->setTransactionToLog("clear token");
    }

    /**
     * Name: setLog
     * Desc: 异常情况，记录log
     * Creator: liuzhen<liuzhen12@lenovo.com>
     * CreatedDate: 20160906
     * Modifier:
     * ModifiedDate:
     * @param $message
     */
    public function setLog($message)
    {
        Yii::error($message, self::CATEGORY_EXECUTE);
    }

    /**
     * Name: setTransactionToLog
     * Desc: 记录被执行的sql
     * Creator: zhanghy25
     * CreatedDate: 20161110
     * Modifier:
     * ModifiedDate:
     * @param $message
     */
    public function setTransactionToLog($arg)
    {
        Yii::info($arg, self::CATEGORY_EXECUTE);
    }

    /**
     * Name: approver_list
     * Desc: get approver list from csc
     * User: CHENDAN5
     * Date: 18-12-18
     * @param $d
     * @return mixed
     */
    public function approver_list($d)
    {
        $url = $this->url."approve_list";
        $data = [
            'query' => $d,
            'page_num' => 1,
            'page_size' => 40,
            'columnList' => []
        ];
        return $this->_exec($url ,$data);
    }

    /**
     * Name: tickets_detail
     * Desc: get a series of ticket detail
     * User: CHENDAN5
     * Date: 19-1-14
     * @param $data
     * @return array|mixed
     */
    public function tickets_detail($data)
    {
        $login_url = $this->url."ticket_detail";
        return $this->_exec($login_url, $data, true, true);
    }

}