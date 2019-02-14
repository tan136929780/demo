<?php

/**
 * Displays a variable.
 * This method achieves the similar functionality as var_dump and print_r
 * but is more robust when handling complex objects such as Yii controllers.
 * @param mixed $var variable to be dumped
 * @param integer $depth maximum depth that the dumper should go into the variable. Defaults to 10.
 * @param boolean $highlight whether the result should be syntax-highlighted
 */
function p($var, $depth = 10) {
    \yii\helpers\VarDumper::dump($var, $depth, true);
}

/**
 * @return mixed|null|string
 */
function pp() 
{
    echo '<pre>';
    var_dump( func_get_args() );
    echo '</pre>';    
}

if (!function_exists('array_column')) {
    /**
     * Name: array_column
     * Desc: 实现php5.5的array_column函数功能
     * User: baiyulong<baiyl3@lenovo.com>
     * Date: 2015-07-02
     * @param $input
     * @param $columnKey
     * @param null $indexKey
     * @return array
     */
    function array_column($input, $columnKey, $indexKey = null)
    {
        $tmp = [];
        foreach ($input as $k => $v) {
            if (is_null($columnKey)) {
                if (is_null($indexKey) || !isset($v[$indexKey])) {
                    return $input;
                } else {
                    $tmp[$v[$indexKey]] = $v;
                }
            } else {
                if (is_null($indexKey) || !isset($v[$indexKey])) {
                    $tmp[] = $v[$columnKey];
                } else {
                    $tmp[$v[$indexKey]] =  $v[$columnKey];
                }
            }
        }
        return $tmp;
    }
}

function print_stack_trace()
{
    $html = '';
    $array =debug_backtrace();
    //print_r($array);//信息很齐全
    unset($array[0]);
    foreach($array as $row)
    {
        if(!isset($row['file'])){
            continue;
        }
        $html .=$row['file'].':'.$row['line'].'行,调用方法:'.$row['function']."<p>";
    }
    var_dump($html);
    return $html;
}

function findModuleByCountry($country, $map)
{
    $tmpMap = [];
    $result = strtolower($country);

    foreach ($map as $key => $value) {
        if (is_array($value)) {
            foreach ($value as $eachValue) {
                $tmpMap[$eachValue] = $key;
            }
        } else {
            $tmpMap[$value] = $key;
        }
    }
    if (isset($tmpMap[$country])) {
        $result = strtolower($tmpMap[$country]);
    }

    return $result;
}

/*对附件的字符串截取函数  */
function trim_attach_name($name)
{
   $new_name  =   substr($name,strpos($name,";",strpos($name,';',0)+1)+1);
    return $new_name;
}


/*
 * 公共函数，用于用户名称的显示
  无user id 时，取当前 用户user id
*/
function display_username($user_id = null){
  if(!$user_id){
         $user_id ="";
  }

}
