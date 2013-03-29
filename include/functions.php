<?php

/**
 * 获取匹配的字符串
 * @param $str   源字符串
 * @param $reg  正则
 * @param $i  返回匹配的第几个
 * @return bool 失败
 */
function get_match_str($reg,$str,$i = 0){
    preg_match_all($reg,$str,$matches);
    if(!empty($matches[0])){
        if(isset($matches[0][$i])) return $matches[0][$i];
    }
    return false;
}

/**
 * 获取$_REQUEST参数，包括$_GET,$_POST,$_COOKIE
 * @param $name 参数名称
 * @param $default 默认值返回值
 */
function GET($name,$default = ''){
    return isset($_REQUEST[$name]) ? $_REQUEST[$name] : $default;
}

/**
 * 获取$_REQUEST参数，包括$_GET,$_POST,$_COOKIE
 * @param $name 参数名称
 * @param $default 默认值返回值
 */
function get_query($name,$default = ''){
    return isset($_GET[$name]) ? $_GET[$name] : $default;
}

/**
 * 获取$_POST参数
 * @param $name 参数名称
 * @param $default 默认值返回值
 */
function get_post($name,$default = ''){
    return isset($_POST[$name]) ? $_POST[$name] : $default;
}

/**
 * 获取$_COOKIE参数
 * @param $name 参数名称
 * @param $default 默认值返回值
 */
function get_cookie($name,$default = ''){
    return isset($_COOKIE[$name]) ? $_COOKIE[$name] : $default;
}

/**
 * 获取$_REQUEST参数，包括$_GET,$_POST,$_COOKIE
 * @param $name 参数名称
 * @param $default 默认值返回值
 */
function get_request($name,$default = ''){
    return isset($_REQUEST[$name]) ? $_REQUEST[$name] : $default;
}

/**
 * 获取一个请求是否是POST方式
 * @return bool
 */
function is_post(){
    return strtolower( $_SERVER['REQUEST_METHOD']) == 'post';
}

//提示信息
//  $msg是要提示的内容，$goto是提示完要跳到得页面
function alert_message($msg,$goto=""){
    echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
    echo '<html xmlns="http://www.w3.org/1999/xhtml">';
    echo '<head>';
    echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
    echo '<script type="text/javascript">';
    echo 'alert("'.$msg.'");';
    if($goto != ""){
        echo 'document.location.href="'.$goto.'";';
    }else{
        echo 'if(histroy.lenght > 1) history.back(); else window.close();';
    }
    echo '</script>';
    echo '</head>';
    echo '<body></body>';
    echo '</html>';
    exit;
}

/**
 * 显示成功的tip消息
 * @param $msg 消息文本
 * @param string $goto 跳转地址
 * @param int $delay  停留几秒
 */
function tip_message_success($msg = '',$goto = '',$delay = 1){
    tip_message($msg,$goto,$delay,'#FAFEEF');
}
/**
 * 显示错误tip消息
 * @param $msg 消息文本
 * @param string $goto 跳转地址
 * @param int $delay  停留几秒
 */
function tip_message_error($msg = '',$goto = '',$delay = 5){
    tip_message($msg,$goto,$delay,'#FFCFCF');
}
/**
 * 显示提醒tip消息
 * @param $msg 消息文本
 * @param string $goto 跳转地址
 * @param int $delay  停留几秒
 */
function tip_message_notice($msg = '',$goto = '',$delay = 5){
    tip_message($msg,$goto,$delay,'#FFFFDD');
}

/**
 * 显示tip消息
 * @param string $msg 消息文本
 * @param string $goto 跳转地址
 * @param int $delay 停留几秒
 * @param string $color 消息显示颜色
 */
function tip_message($msg= '',$goto="",$delay = 0, $color = '#FFFFDD'){
    echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
    echo '<html xmlns="http://www.w3.org/1999/xhtml">';
    echo '<head>';
    echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
    if($goto != ""){
        if($goto == 'back'){
            echo '<script type="text/javascript">setTimeout(function(){if(window.history.length > 1) window.history.back(); else window.close();},'.($delay*1000).');</script>';
        } else{
            echo '<meta http-equiv="refresh" content="'.$delay.';url='.$goto.'">';
        }
    }else{
        echo '<script type="text/javascript">setTimeout(function(){window.close();},'.($delay*1000).');</script>'; //if(window.history.length > 1) window.history.back(); else
    }
    echo '</head>';
    echo '<body style="margin-top:200px;">';
    echo '<div style="width: 800px;margin:0 auto;text-align:center; background-color:'.$color.';border: 1px solid #E0E0E0;padding: 1em;">'.$msg;
    echo '，'.$delay.'秒后自动关闭';
    if($goto != "") echo '，<a href="'.$goto.'">点此继续</a>';
    echo '</div>';
    echo '</body>';
    echo '</html>';
    exit;
}

/**
 * 输出一般的日志
 * @param $date 日志内容
 */
function echo_log(){
    echo_message(func_get_args(),'#000');
}

/**
 * 输出一般的日志
 * @param $date 日志内容
 */
function echo_notice(){
    echo_message(func_get_args(),'#fcc90d');
}

/**
 * 输出一般的错误日志
 * @param $date 日志内容
 */
function echo_error(){
    echo_message(func_get_args(),'#ff4400');
}

/**
 * 输出一般的成功日志
 * @param $date 日志内容
 */
function echo_success(){
    echo_message(func_get_args(),'#5F9504');
}

/**
 * 日志输出实现
 * @param $date 日志内容
 * @param $color 日志颜色值
 */
function echo_message($data,$color){
    if(defined('SHOW_LOG') && SHOW_LOG == false){
        echo '<p style="display: none;">> ';
    } else{
        echo '<p style="font:400 12px/1.6em \'Courier New\',\'宋体\',sans-serif; margin:1px;border-bottom:solid 1px #ddd;color:'.$color.';">> ';
    }
    foreach($data as $k => $v){
        print_r($v);
        if($k != (count($data) -1))echo ' | ';
    }
    echo '</p>';
}

/**
 * 跳转到指定的页面
 * @param  $url url地址
 * @return void
 */
function goto_url($url){
//        echo "<script type='text/javascript'>";
//		if($url != ""){
//		 	echo "document.location.href='$url';";
//		}
//		echo "</script>";
    header("Location:$url");
    exit;
}

/**
 * 根据文件创建文件所在的文件夹,这里只能接收文件目录参数，不带文件名的
 * @param  $dir 文件路径
 * @return void
 */
function create_folder($dir) {
    if (!is_dir($dir)) {
        $temp = explode('/', $dir);
        $cur_dir = '';
        for ($i = 0; $i < count($temp); $i++) {
            $cur_dir .= $temp[$i] . '/';
            if (!is_dir($cur_dir)) {
                @mkdir($cur_dir,0777);
                @chmod($cur_dir,0777);
            }
        }
    }
}

/**
 * 根据文件创建文件所在的文件夹,这里可以接受带文件名的路径
 * @param  $path 文件路径
 * @return void
 */
function createFolder($path) {
    if(preg_match("/\/$/",$path))
        $path = substr($path,0,strlen($path)-1);
    if (! file_exists ( $path )) {
        createFolder ( dirname ( $path ) );
        $dir = dirname ( $path);
        if(!file_exists ( $dir )){
//                echo $dir;
            @mkdir ( $dir, 0777 );
            @chmod( $dir,0777);
        }
    }
}


/**
 * @param $id
 * @return
 */
function get_img_path($id){
    global $DOMAIN_LIST;
    $lt = $id%5;
    return $DOMAIN_LIST['images'][$lt];

}

/**
 * 移除静态化后文件的BOM签名信息，不然显示保存
 */
function remove_bom($filename) {
    $contents = file_get_contents ( $filename );
    $charset [1] = substr ( $contents, 0, 1 );
    $charset [2] = substr ( $contents, 1, 1 );
    $charset [3] = substr ( $contents, 2, 1 );
    if (ord ( $charset [1] ) == 239 && ord ( $charset [2] ) == 187 && ord ( $charset [3] ) == 191) {
        $rest = substr ( $contents, 3 );
        $filenum = fopen ( $filename, "w" );
        flock ( $filenum, LOCK_EX );
        fwrite ( $filenum, $rest );
        fclose ( $filenum );
        return 'BOM REMOVE SUCCESS';
    } else {
        return "BOM NOT FOUND";
    }
}


/**
 * 返回处理成功后的json数据
 * @param  $data 返回的数据值，可以是string或者array
 * @return void
 */
function json_view($view='',$status = 1){
    $result = array(
        'status' => $status,
        'data' => array('view' => $view )
    );
    echo json_encode($result);
    exit();
}

/**
 * 返回处理成功后的json数据
 * @param  $data 返回的数据值，可以是string或者array
 * @return void
 */
function json_success($data='',$callback = ''){
    $result = array(
        'status' => 1,
        'data' => $data
    );
    $json = json_encode($result);
    if(!empty($callback)){
        if($callback == 'return') return $json;
        echo '<script type="text/javascript">'.$callback.'('.$json.');</script>';
    } else echo $json;
    exit();
}

/**
 * 返回处理失败后的json数据
 * @param  $error 错误提示消息
 * @return void
 */
function json_failure($error='',$callback = ''){
    $result = array(
        'status' => 0,
        'statusText' => $error
    );
    $json = json_encode($result);
    if(!empty($callback)){
        echo $callback.'('.$json.');';
    } else echo $json;
    exit();
}

/*---------------------------下面是关于数组的扩展方法---------------------------------*/

/**
 * 数组的模糊搜索
 * @param  $arr 数组
 * @param  $field 被搜索的字段,支持多个字段并列查询,多个字段之间用竖线 | 分隔
 * @param  $key 搜索关键字
 * @param  $mode 匹配模式 1 首匹配 2 首位都匹配 3 尾匹配 4 精确匹配,5 中间匹配
 * @return void
 */
function array_fuzzy_search($arr = array(),$field= 'name',$key = '',$mode = 2){
    $result = array();
    foreach( $arr as $k => $v ){
        $fs = explode('|',$field);
        $strs = array();
        if(count($fs) > 1){
            foreach($fs as $k1 => $v1){
                array_push($strs, $v[$v1]);
            }
        } else{
            if(is_array($v) && isset($v[$field])) array_push($strs, $v[$field]);
            else if(is_string($v)) array_push($strs, $v);
            else return false;
        }

        foreach( $strs as $k2 => $v2 ){
            if(($p = strpos( $v2 , $key )) !== false ){
                if($mode == 1 && $p == 0) $result[$k] = $v;
                else if($mode == 2) {
                    $v['sort'] = $p;
                    $result[$k] = $v;
                }
                else if($mode == 3 && substr($v2,-strlen($key)) == $key) $result[$k] = $v;
                else if($mode == 4 && $v2 == $key) $result[$k] = $v;
                else if($mode == 5 && $p > 0 ) {
                    $v['sort'] = $p;
                    $result[$k] = $v;
                }
                break;
            }
        }
    }
    if($mode == 2 || $mode == 5){
        $result = array_sort($result,'sort');
    }
    return $result;
}

/**
 * 搜索二维数组的值，返回序列和key键
 * @param array $arr
 * @param string $field
 * @param string $q
 * @return array|bool
 */
function array_find($arr=array(),$field='', $q=''){
    $i = 1; $r = false;
    foreach( $arr as $k => $v ){
        if($v[$field] == $q){
            $r = array($i,$k);
        }
        $i++;
    }
    return $r;
}

/**
 * 过滤一维数组中为空的项
 * @param  $arr 数组
 * @return bool
 */
function array_filter_null($arr = null){
    if(empty($arr)) return false;
    foreach($arr as $k => $v){
        if(empty($v)) unset($arr[$k]);
    }
    return $arr;
}

/**
 * 数组排序
 * @param  $array 数组
 * @param  $keyname 按那个字段排
 * @param string $sortby 升序还是降序
 * @return array 返回排序后的数组
 */
function array_sort($array,  $keyname = null, $keepkey = true, $sortby = 'asc') {
   if(empty($array)) return $array;
   $myarray = $inarray = array();
   //First store the keyvalues in a seperate array
   foreach ($array as $i => $befree) {
       $myarray[$i] = $befree[$keyname];
   }
   $sortby =  strtolower($sortby);
   //Sort the new array by
   switch ($sortby) {
       case 'asc':
       //Sort an array and maintain index association...
       asort($myarray);
       break;
       case 'desc':
       //Sort an array in reverse order and maintain index association
       arsort($myarray);
       break;
   }
   //Rebuild the old array
   foreach ( $myarray as $key=> $befree) {
       if($keepkey) $inarray[$key] = $array[$key];
       else $inarray[] = $array[$key];
   }
   unset($array,$myarray);

   return $inarray;
}

/**
 * 获取二维数组第一个项的值
 *
 * $array2 = array(
 *      array('id'=>1,'name'=>'first' ),
 *      array('id'=>2,'name'=>'second')
 * )
 * if $key = 'id'
 *      result = 1
 *
 * @param  $array2 二维数组
 * @param string $key 指定项的值
 * @return bool|mixed 失败返回false，成功返回值
 */
function get_array2_first_value($array2,$key = ''){
    if(!empty($array2)){
        $it = array_shift($array2);
        array_unshift($array2,$it);
        if(!empty($key) && isset($it[$key]))
            return $it[$key];
        return $it;
    }
    return false;
}
/**
 * 从某个二维数组里获取指定字段组成的key/value形式的一纬数组
 *
 * $array2 = array(
 *      array('id'=>1,'name'=>'first' ),
 *      array('id'=>2,'name'=>'second')
 * )
 * if $key = 'id' && $value = 'name'
 *      result = array('1'=>'first','2'=>'second')
 *
 * @param  $array2
 * @param  $field1
 * @param  $field2
 * @return array
 */
function get_kvarray1_from_array2($array2,$key, $value){
    $kvarray = array();
    foreach($array2 as $k => $v){
        if(!isset($v[$key]) ||  !isset($v[$value])) break;
        $kvarray[$v[$key]] = $v[$value];
    }
    return $kvarray;
}

/**
 * 从某个二位数组中获取一个简单的一维数组
 *
 * $array2 = array(
 *      array('id'=>1,'name'=>'first' ),
 *      array('id'=>2,'name'=>'second')
 * )
 * if $value = 'id'
 *      result = array('1','2')
 *
 * @param  $array2 数组
 * @param  $value 作为值得字段名
 * @return array
 */
function get_varray_from_array2($array2,$value = 'id'){
    $varray = array();
    foreach($array2 as $k => $v){
        if(!isset($v[$value])) break;
        array_push($varray,$v[$value]);
    }
    unset($array2);
    return $varray;
}

/**
 * 从数组中取出第一层key组成一个简单的一维数组
 *
 * $array2 = array(
 *      '21' => array('id'=>1,'name'=>'first' ),
 *      '31' => array('id'=>2,'name'=>'second')
 * )
 *      result = array('21','31')
 *
 * @param  $array2 数组
 * @param  $value 作为值得字段名
 * @return array
 */
function get_kkarray_from_array2($array2,$exclude = array()){
    $varray = array();
    foreach($array2 as $k => $v){
        if(!in_array($k,$exclude)) array_push($varray,$k);
    }
    unset($array2);
    return $varray;
}

/**
 * 从某个二维数组中获取一个以指定字段值为key的二维数组，便于直接使用key/value的方式存取
 *
 * $array2 = array(
 *      array('id'=>1,'name'=>'first' ),
 *      array('id'=>2,'name'=>'second')
 * )
 * if $key = 'id'
 *      result = array(
 *          '1' => array('id'=>1,'name'=>'first' ),
 *          '2' => array('id'=>2,'name'=>'second')
 *      )
 *
 * @param  $array2
 * @param string $key
 * @return array
 */
function get_karray_from_array2($array2,$key = 'id'){
    $karray = array();
    foreach($array2 as $k => $v){
        if(!isset($v[$key])) break;
        $karray[$v[$key]] = $v;
    }
    return $karray;
}

/**
 * 从某个二维数组中获取一个以指定的2个字段值为key的二维数组，便于直接使用key/value的方式存取
 *
 * $array2 = array(
 *      array('id'=>1,'name'=>'first' ),
 *      array('id'=>2,'name'=>'second')
 * )
 * if $key1 = 'id' && $key2 = 'name'
 *      result = array(
 *          '1first' => array('id'=>1,'name'=>'first' ),
 *          '2second' => array('id'=>2,'name'=>'second')
 *      )
 *
 * @param  $array2
 * @param string $key
 * @return array
 */
function get_k2array_from_array2($array2,$key1,$key2){
    $k2array = array();
    foreach($array2 as $k => $v){
        if(!isset($v[$key1]) || !isset($v[$key2])) break;
        $k2array[$v[$key1].$v[$key2]] = $v;
    }
    return $k2array;
}

/**
 * 从一个简单的二维数据里得到一个1对多的二维数组，主要是处理数据库一对多的那种记录
 *
 * $array2 = array(
 *      array('id'=>1,'name'=>'first' ),
 *      array('id'=>2,'name'=>'second'),
 *      array('id'=>1,'name'=>'another first')
 * )
 * if $key = 'id' && $value = 'name'
 *      result = array(
 *          '1' => array('first','another first' ),
 *          '2' => array('second')
 *      )
 *
 * @param  $array1 数组
 * @param  $field1 字段名一
 * @param  $field2 字段名二
 * @return array 计算好的一对多数组
 */
function get_kvarray2_from_array1($array1,$key,$value = ''){
    $array2 = array();
    $iv = '';
    $at = array();
    $i = 0;
    foreach($array1 as $k =>$v){
        $i++;
        if(!isset($v[$key]) || (!empty($value) && !isset($v[$value]))) break;
        $v1 = $v;
        if(!empty($value)) $v1 = $v[$value];
        if($iv != $v[$key]){
            if(!empty($at)){
                if(isset($array2[$iv])) $array2[$iv] = array_merge($array2[$iv],$at);
                else $array2[$iv] = $at;
                $at = array();
            }
            $iv = $v[$key];

            array_push($at,$v1);
        } else{
            array_push($at,$v1);
        }
    }
    if(!empty($at)){
        if(isset($array2[$iv])){
            $array2[$iv] =  array_merge($array2[$iv],$at);
        }else {
            $array2[$iv] = $at;
        }
    }
    return $array2;
}

/**
 * 排序一个数组按照另外一个数据的顺序，中间通过一个字段关联,
 * 这通常用在我们先获取到一个排序的ID列表，然后再进一步查询其详细记录的时候，
 * 就需要用前面的id列表的顺序来排序后面查询出来的详细记录，比如sphinx的搜索
 * @param $array1   待排序的数组
 * @param $array2   参照数组
 * @param string $kye1 关联字段在array1中的key值
 * @param string $kye2 关联字段在array2中的key值
 * @return array 返回排好序的数组
 */
function sort_array1_by_array2($array1,$array2,$key1 = 'id', $key2 = 'id'){
    if(empty($key1)) $list = $array1;
    else $list = get_karray_from_array2($array1,$key1);
    $sortList = array();
    foreach($array2 as $k => $v){
        if(!empty($key2)) $key = $v[$key2];
        else $key = $v;
        if(isset($list[$key])){
            if(empty($key1)) $sortList[$key] = $list[$key];
            else $sortList[] = $list[$key];
        }
    }
    unset($array1,$array2,$list);

    return $sortList;
}

/**
 * 获取数组的随机个数
 * @param $array1  数组
 * @param $ct    几个
 * @return array
 */
function get_array_rand_part($array1,$ct){
    $arr = array();
    if(!empty($array1)){
        $rct = count($array1);
        if($rct== 1) return $array1;
        if($ct > $rct) $ct = $rct;

        // 随机抽取指定个数
        $randKeys = array_rand($array1,$ct);

        foreach($randKeys as $k => $v){
            $arr[] = $array1[$v];
        }
        unset($array1);
    }
    return $arr;
}

/* -------------------------数组方法扩展结束---------------------------------------*/
/**
 * 保存文件内容
 * @param  $file 文件地址
 * @param  $content 内容
 * @param  $newfile 是否新建文件
 * @return bool
 */
function save_file_content($file, $content, $newfile = false){
    if( $newfile == true || (file_exists($file) && !empty($content))){
        $fp = fopen($file, "w");
        fwrite($fp, $content);
        fclose($fp);
        return true;
    }
    return false;
}

/**
 * 远程读取图片
 * @param  $url
 * @return mixed
 */
function get_img_by_curl($url){
    set_time_limit(0);
    $ch = curl_init();
    curl_setopt ($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT,30);
//    curl_setopt($ch, CURLOPT_HEADER, 1);
    $file_contents = curl_exec($ch);
    curl_close($ch);
    return $file_contents;
}

/**
 * @param  $url 远程地址
 * @param  $path 本地地址
 * @param bool $copy 是否拷贝
 * @return bool
 */
function saveImage($url,$path,$copy = false, $echo = true) {
    date_default_timezone_set ( 'PRC' );

    $newfname = $path;
    if (file_exists ( $newfname )){
        return false;
    }
//    echo "URL:$url,PATH:$path";
    createFolder ($newfname);
    if($copy){
        if(!copy($url,$path)){
            if($echo) echo "copy_error";
            return false;
        }
    } else{
//        echo "Remote";
        $i = 0;
        while(read_and_save_img($url,$newfname,false) === false){
            $i++;
            if($i > 5) break;
        };
    }
    @chmod($newfname,0777);
    return true;
}

function read_and_save_img($url,$newfname, $echo = true){
    $file = get_img_by_curl ( $url );
    if ($file) {
        $fp = @fopen ( $newfname, "w" );
        if($echo) echo "WriteFile";
        @fwrite ( $fp, $file );
        @fclose ( $fp );
//        echo(@exif_imagetype($newfname));
        if(@exif_imagetype($newfname) === false){
            if($echo) echo "Retry ";
            return false;
        }
        return true;
    } else{
        if($echo) {
            echo $url;
            echo " | down_error";
        }
        return null;
    }
}

/**
 * 获取用户ip
 * @return string $realip
 */
function get_client_ip($long = true) {
    if (isset ( $_SERVER )) {
        if (isset ( $_SERVER ["HTTP_X_FORWARDED_FOR"] )) {
            $realip = $_SERVER ["HTTP_X_FORWARDED_FOR"];
        } else if (isset ( $_SERVER ["HTTP_CLIENT_IP"] )) {
            $realip = $_SERVER ["HTTP_CLIENT_IP"];
        } else {
            $realip = $_SERVER ["REMOTE_ADDR"];
        }
    } else {
        if (getenv ( "HTTP_X_FORWARDED_FOR" )) {
            $realip = getenv ( "HTTP_X_FORWARDED_FOR" );
        } else if (getenv ( "HTTP_CLIENT_IP" )) {
            $realip = getenv ( "HTTP_CLIENT_IP" );
        } else {
            $realip = getenv ( "REMOTE_ADDR" );
        }
    }
    if($long) $realip = sprintf("%u", ip2long($realip));
    return $realip;
}

/**
 * 获取referer
 * @return string $ref
 */
function get_client_referer($isOut = true,$dm = '') {
    $ref = '';
    if (isset ( $_SERVER ) && isset ( $_SERVER ["HTTP_REFERER"] )) {
        $ref = $_SERVER ["HTTP_REFERER"];
    }
    // 排除当前域
    if($isOut && $dm != '' && stripos($ref,$dm) !== false){
        $ref = '';
    }
    return $ref;
}

/**
 * 判断来路用户是否为机器人
 * @return bool
 */
function is_bot(){
    $ua = strtolower($_SERVER['HTTP_USER_AGENT']);
    $botchar = "/(bot|spider|yahoo)/i";
    if(preg_match($botchar, $ua)) {
        return true;
    }else{
        return false;
    }
}

// 多个前台、后台和抓取3个系统之前进行http调用使用的密钥
function get_access_key(){
    $ts = strtotime(date('Y-m-d H:i',time()));
    return md5($ts.md5(__ROOT__));
}

/**
 * 转义敏感字符，防止sql注入
 * @param $content 内容
 * @return array|string 返回内容
 */
function quotes($content) {
    //如果magic_quotes_gpc=Off，那么就开始处理
    if (!get_magic_quotes_gpc()) {
        //判断$content是否为数组
        if (is_array($content)) {
            //如果$content是数组，那么就处理它的每一个单无
            foreach ($content as $key=>$value) {
                $content[$key] = addslashes($value);
            }
        } else {
            //如果$content不是数组，那么就仅处理一次
            $content = addslashes($content);
        }
    }
    //返回$content
    return $content;
}
/**
 * @fileoverview 用于截断包含中文（或其他多字节的？）utf8编码的字符串
 */
function utf8_cut_string($str, $len = 30, $truncation = '…') {
    if (utf8_strlen($str) > $len) {
        if (seems_utf8($str[$len-1])) {	// 判断截断字符串的最后一个字符是否是utf8编码的
            $str = utf8_substr($str, 0, $len);	// 如果是utf8编码的，直接截断输出
        } else {	// 如果不是utf8编码的，因为utf8编码的中文是三个字节进行保存的，则判断该字符和周围字符组成的字符串是否符合utf8编码
            if(seems_utf8($str[$len-3].$str[$len-2].$str[$len-1]))
                $str = utf8_substr($str, 0, $len-3);
            elseif(seems_utf8($str[$len-2].$str[$len-1].$str[$len]))
                $str = utf8_substr($str, 0, $len-2);
            elseif(seems_utf8($str[$len-1].$str[$len].$str[$len+1]))
                $str = utf8_substr($str, 0, $len-1);
            else // 这个else应该不用也是可以的
                $str = utf8_substr($str, 0, $len);
        }
        $str .= $truncation;
    }
    return $str;
}

// Returns true if $string is valid UTF-8 and false otherwise.
function is_utf8($string) {

    // From http://w3.org/International/questions/qa-forms-utf-8.html
    return preg_match('%^(?:
          [\x09\x0A\x0D\x20-\x7E]            # ASCII
        | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
        |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
        | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
        |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
        |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
        | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
        |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
    )*$%xs', $string);

} // function is_utf8

/**
 * Checks to see if a string is utf8 encoded.
 *
 * NOTE: This function checks for 5-Byte sequences, UTF8
 *       has Bytes Sequences with a maximum length of 4.
 *
 * @author bmorel at ssi dot fr (modified)
 * @since 1.2.1
 *
 * @param string $str The string to be checked
 * @return bool True if $str fits a UTF-8 model, false otherwise.
 */
function seems_utf8($str) {
    $length = strlen($str);
    for ($i=0; $i < $length; $i++) {
        $c = ord($str[$i]);
        if ($c < 0x80) $n = 0; # 0bbbbbbb
        elseif (($c & 0xE0) == 0xC0) $n=1; # 110bbbbb
        elseif (($c & 0xF0) == 0xE0) $n=2; # 1110bbbb
        elseif (($c & 0xF8) == 0xF0) $n=3; # 11110bbb
        elseif (($c & 0xFC) == 0xF8) $n=4; # 111110bb
        elseif (($c & 0xFE) == 0xFC) $n=5; # 1111110b
        else return false; # Does not match any model
        for ($j=0; $j<$n; $j++) { # n bytes matching 10bbbbbb follow ?
            if ((++$i == $length) || ((ord($str[$i]) & 0xC0) != 0x80))
                return false;
        }
    }
    return true;
}

function utf8_strlen($str) {
    $count = 0;
    $len = strlen($str);
    for($i=0; $i<$len; $i++) {
        $count++;
        if(ord($str[$i]) > 127){
            $count++;
            $i += 2;
        }
    }
    return $count;
}

function utf8_substr($str, $from, $len) {
    for($i=0; $i<$len; $i++) {
        if(ord($str[0]) > 127){
            if ($i<$len) {
                $new_str[] = substr($str, 0, 3);
                $str = substr($str, 3);
            }
            $i++;
        } else {
            $new_str[] = substr($str, 0, 1);
            $str = substr($str, 1);
        }
    }
    return implode('',$new_str);
}

/**
 * 获取url地址中的参数部分，返回字符串
 * @param string $url url地址
 * @return string 返回字符串
 */
function get_query_string_from_url($url = ''){
    $str = '';
    if(!empty($url)) {
        $tmp = parse_url($url);
        if(isset($tmp['query'])) $str = $tmp['query'];
    }
    return $str;
}

/**
 * 获取url地址中的参数部分，返回数组
 * @param string $url url地址
 * @return array 返回参数名为key，参数值为value的数组
 */
function get_query_array_form_url($url = ''){
    $arr = array();
    $query = get_query_string_from_url($url);
    if(!empty($query)) {
        $tmp = explode('&',$query);
        if(!empty($tmp)){
            foreach($tmp as $k => $v){
                $tmp1 = explode('=',$v);
                $arr[$tmp1[0]] = $tmp1[1];
            }
        }
    }
    return $arr;
}

/**
 * 计算剩余时间
 */
function get_timeleft($deadline){
    $t = $deadline - time();
    if($t <= 0) return 0;
    $dt = 3600*24;
    $d = (int)($t/$dt);
    $h = (int) (($t%$dt)/3600);
    $m = (int) ((($t%$dt)%3600)/60);
    $s = (int) ((($t%$dt)%3600)%60);
    $h = sprintf("%02s",$h);
    $m = sprintf("%02s",$m);
    $s = sprintf("%02s",$s);
    return array($deadline,$d,$h,$m,$s);
}

/**
 * 获取星期几的格式
 * @param $ts
 * @return mixed
 */
function get_weekday_long($ts){
    global $WEEK_DAY_LONG;
    return $WEEK_DAY_LONG[date('w',$ts)];
}

/**
 * 获取周几的格式
 * @param $ts
 * @return mixed
 */
function get_weekday_short($ts){
    global $WEEK_DAY_SHORT;
    return $WEEK_DAY_SHORT[date('w',$ts)];
}

/**
 * 获取当前时间是今天，明天还是后天
 * @param $d 时间戳
 * @return string
 */
function get_today_tomorrow($d){
    $t='';
    $d = date('Y-m-d',$d);
    if($d == date('Y-m-d')) $t='今天';
    else if($d == date("Y-m-d",strtotime("+1 day"))) $t='明天';
    else if($d == date("Y-m-d",strtotime("+2 day"))) $t='后天';
    return $t;
}

/**
 * 获取文件夹下面的文件列表
 * @param string $dir 目录
 * @param string $white_list 过滤后缀名
 * @param bool $include_dir  是否包含子目录
 * @param string $reg  文件名匹配
 * @return array|bool
 */
function get_dir_files($dir = '', $white_list = '', $subdir = false, $reg = ''){
    if(empty($dir)) return false;
    if(!preg_match('/\/$/',$dir)) $dir .= '/';
    if(empty($white_list)){
        $white_list = array("html","css","js","htm","data","xml");
    }

    $handle=opendir($dir);
    //定义用于存储文件名的数组
    $array_file = array();
    while (false !== ($file = readdir($handle)))
    {
        if ($file != "." && $file != "..") {
            if(empty($reg) || preg_match($reg,$file)){ // 文件名规则匹配
                if(!is_dir($dir.$file)){
                    $t= strtolower(substr(strrchr($file, "."), 1));
                    if(in_array($t,$white_list)){
                        $array_file[] = array(
                            "name" => $file,
                            "isdir" => 0
                        );
                    }
                } else {
                    if($subdir){// 包含目录的时候 才保存目录
                        $array_file[] = array(
                            "name" => $file,
                            "isdir" => 1
                        );
                    }
                }
            }
        }
    }
    closedir($handle);
    return $array_file;
}
?>