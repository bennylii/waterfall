<?php
/**
 * Created by JetBrains PhpStorm.
 * User: SPRINGWANG
 * Date: 13-3-28
 * Time: 上午9:15
 * To change this template use File | Settings | File Templates.
 */

class SwfUpload {

    public $files; //需赋值的$files.为$_FILES
    public $params; //次要的附加参数
    public $upload_name = "Filedata"; //接收上传来的标示名。flash默认"Filedata"
    public $max_file_size_in_bytes = 2097152; //上传大小限制默认2M
    public $max_size = 2000; //图片最大宽高注意允许的宽高过大会造成服务器资源消耗。并造成执行30秒超时。
    public $MAX_FILENAME_LENGTH = 260; //最长文件名
    public $extension_whitelist = array (
        "jpg",
        "gif",
        "png",
        "jpeg"
    ); // 允许的后缀
    public $upload_contain = "/uploads/"; //上传图片所在文件夹
    public $log_path = "logs"; //日志记录的目录。请确认是否有此文件夹
    private $save_path; //保存的路径
    private $file_name; //原文件名
    private $file_save_name; //保存文件名
    private $file_save_path; //保存带路径的文件名
    private $file_extension = "";
    private $pic_title;
    private $uploadErrors = array (
        0 => "",
        1 => "超过最大字节",
        2 => "超过最大字节",
        3 => "文件不完整",
        4 => "无上传文件",
        6 => "找不到暂存路径"
    );
    private $width;
    private $height;

    public function SwfUpload() {}

    public function check() {

        $this->file_name = $this->files[$this->upload_name]['name'];
        $this->save_path = dirname(__FILENAME__) . $this->upload_contain;

        //参数设置
        date_default_timezone_set('PRC'); //设定时区

        //检测gd库
        if (function_exists("gd_info")) {
            //检测是否开启gd库
            $gdinfo = gd_info();
            $gdversion = $gdinfo['GD Version']; //获得版本号
            preg_match("/([0-9])\.([0-9]+)\.([0-9]+)/", $gdversion, $ver); //获得特定版本号
            if ($ver[1] < 2 && $ver[3] < 28) //检查库支持。过低版本不能处理gif
            {
                return $this->HandleError("gd低于2.0.28");
            }
        } else {
            return $this->HandleError("没有开启gd库");
        }

        //检测文件大小和POST数据长度
        $POST_MAX_SIZE = ini_get('post_max_size'); //获取php中上传限制参数
        $unit = strtoupper(substr($POST_MAX_SIZE, -1)); //获取K,M,G
        //获取大小倍数
        $multiplier = ($unit == 'M' ? 1048576 : ($unit == 'K' ? 1024 : ($unit == 'G' ? 1073741824 : 1)));
        //设定可接收的最大上传字节数。php.ini中限制和你设定的限制中最小的一个。
        $this->max_file_size_in_bytes = ($this->max_file_size_in_bytes > $multiplier * (int) $POST_MAX_SIZE) ? ($multiplier * (int) $POST_MAX_SIZE) : $this->max_file_size_in_bytes;

        if ((int) $_SERVER['CONTENT_LENGTH'] > $this->max_file_size_in_bytes) {
            //header("HTTP/1.1 500 Internal Server Error"); // 触发一个错误
            return $this->HandleError("文件大小错误");
        }

        // 初步检查文件
        if (!isset ($this->files[$this->upload_name])) {
            return $this->HandleError("无上传文件");
        } else if (isset ($this->files[$this->upload_name]["error"]) && $this->files[$this->upload_name]["error"] != 0) { //发生错误时
                return $this->HandleError($this->uploadErrors[$this->files[$this->upload_name]["error"]]);
        } else if (!isset ($this->files[$this->upload_name]["tmp_name"]) || !@ is_uploaded_file($this->files[$this->upload_name]["tmp_name"])) {
            return $this->HandleError("合法性验证失败");
        } else if (!isset ($this->files[$this->upload_name]['name'])) {
            return $this->HandleError("无文件名");
        }

        // 检查php.ini中上传文件大小允许值。
        $file_size = @ filesize($this->files[$this->upload_name]["tmp_name"]);
        if (!$file_size || $file_size > $this->max_file_size_in_bytes) {
            return $this->HandleError("文件过大");
        }

        if ($file_size <= 0) {
            return $this->HandleError("0字节文件");
        }

        // 检查文件后缀
        $path_info = pathinfo($this->files[$this->upload_name]['name']);
        $this->file_extension = strtolower($path_info["extension"]);
        $is_valid_extension = in_array($this->file_extension, $this->extension_whitelist); //检查后缀是否在数组中

        if (!$is_valid_extension) {
            return $this->HandleError("文件后缀错误");
        }

        // 去掉后缀名的文件名，作为图片的标题
        $this->pic_title = str_replace('.'.$this->file_extension,'',$this->file_name); //此为返回给flash的文件名,用于上传完毕的修改提示
        //文件实际在服务器的名字.结合原文件名+暂存名+上传时间的MD5值。
        $this->file_save_name = md5($this->file_name . date('Y-m-d')) . "." . $this->file_extension;
        $this->file_save_path =  $this->save_path . $this->file_save_name;
        //检查同名文件
        if (file_exists($this->file_save_path)) {
            return $this->HandleError("已有同名文件");
        }
        return true;
    }

    /*-----------------------
   * 检测上传文件的图片编码，并保存。
   * ---------------------------
   * */

    public function upload() {
        //用gd库检查图片的实际编码，并执行相关操作。
        if ($isjpeg = @ imagecreatefromjpeg($this->files[$this->upload_name]["tmp_name"])) { //如果是jpeg
            ////获取原图片大小
            $this->width = imagesx($isjpeg);
            $this->height = imagesy($isjpeg);

            if ($this->width > $this->max_size || $this->height > $this->max_size) {
                //图片大小超过或者需要旋转，启用gd库特别操作
                $new_img = $this->resize_image($isjpeg);

                if (@ imagejpeg($new_img, $this->file_save_path)) {
                    return $this->HandleOk("文件保存成功");
                } else {
                    return $this->HandleError("无法保存");

                }
            }//gd库特别操作end
            else{//图片不超过大小宽高并且无旋转，直接保存
                if (@ move_uploaded_file($this->files[$this->upload_name]["tmp_name"], $this->file_save_path)) {
                    return $this->HandleOk("文件保存成功");
                } else {
                    return $this->HandleError("无法保存");
                }
            }//直接保存end
        } //jpeg完
        else if ($ispng = @ imagecreatefrompng($this->files[$this->upload_name]["tmp_name"])) { //如果是png
            //变成合适大小并旋转
            ////获取原图片大小
            $this->width = imagesx($ispng);
            $this->height = imagesy($ispng);

            if ($this->width > $this->max_size || $this->height > $this->max_size || $this->params["rotation"] != 0) {
                //图片大小超过或者需要旋转，启用gd库特别操作
                $new_img = $this->resize_image($ispng, $this->params["rotation"]);

                if (@ imagepng($new_img, $this->file_save_path)) {
                    return $this->HandleOk("文件保存成功");
                } else {
                    return $this->HandleError("无法保存");

                }
            }//gd库特别操作end
            else{//图片不超过大小宽高并且无旋转，直接保存
                if (@ move_uploaded_file($this->files[$this->upload_name]["tmp_name"], $this->file_save_path)) {
                    return $this->HandleOk("文件保存成功");
                } else {
                    return $this->HandleError("无法保存");

                }
            }//直接保存end
        } //png完
        else if ($isgif = @ imagecreatefromgif($this->files[$this->upload_name]["tmp_name"])) {
                //如果是gif，由于使用imagegif将只保留一帧图片，因此使用普通文件保存方法。
                if (@ move_uploaded_file($this->files[$this->upload_name]["tmp_name"], $this->file_save_path)) {
                    return $this->HandleOk("文件保存成功");
                } else {
                    return $this->HandleError("无法保存");
                }
        } //gif完
        else {
            return $this->HandleError("图过大或编码错");
        }
    }

    /*------------------------------------------------
   * 调整图片大小。宽高大于设定的变小，并旋转图片
   * resize_image("文件资源句柄"，"旋转度")
   *
   * @param string $source接收待处理的文件资源句柄
   * @param int $rotation旋转多少度
   * @return handler $new_img返回处理后的文件资源句柄
   * -----------------------------------------
   * */
    private function resize_image($source) {
        //获取原图片大小
        $max = $this->max_size;
        if ($this->width < $max && $this->height < $max) {
            return $source;
        } else { //有超过的边
            if ($this->width > $max && $this->height > $max) { //都超过时
                if ($this->width >= $this->height) { //宽大于高
                    $new_width = $max; //宽为max
                    $new_height = $max / ($this->width / $this->height); //保持宽高比
                } else {
                    $new_height = $max;
                    $new_width = $max / ($this->height / $this->width);
                }
            } else{
                if ($this->width > $max) { //只有宽超过
                    $new_width = $max;
                    $new_height = $this->height / ($this->width / $max);
                }
                if ($this->height > $max) { //只有高超过
                    $new_height = $max;
                    $new_width = $this->width / ($this->height / $max);
                }
            }
            $dest = imagecreatetruecolor($new_width, $new_height);
            //调整图片大小。将原图片填充到目的图片上
            imagecopyresized($dest, $source, 0, 0, 0, 0, $new_width, $new_height, $this->width, $this->height);
            return $dest;
        }
    }

    /*---------------------------------
   * 上传失败后的操作。用于输出xml，并记录相关参数
   * 接收参数:
   * @param string $message输出给flash的文字提示
   * ----------------------------------
   * */

    private function HandleError($message) { //发生错误时的日志记录。
        return array(
            'error' => $message,
            'fileName' => $this->file_name,
        );

/*
        $xml = "<?xml version='1.0' encoding='utf-8'?>";
        $xml .= "<root><information errorId='1' message='" . $message . "'/></root>";
        echo $xml;
//下面为日志记录
        $log = date("[H:i:s]");
        $log .= "【错误消息:】" . $message;
        $log .= "【IP:】" . $this->getIP();
        $log .= "【用户名:】" . $this->getUser();
        $log .= "【Session:】" . $this->session;
        $log .= "【文件名:】" . $this->files[$this->upload_name]['name'];
        $log .= "【图片字节数:】" . $this->files[$this->upload_name]['size'];
//$log .= "【Cookie:】" . $this->params['cookie'] ;
        $log .= "【AllowComment:】" . $this->params['allowComment'];
        $log .= "【AllowReprint:】" . $this->params['allowReprint'];
        $log .= "【Rotaion:】" . $this->params['rotation'] . "\r\n";
//日志默认目录：logs 请确定是否有这个目录
        @ error_log($log, 3, "./" . $this->log_path . "/errorlog" . date("[Y-m-d]") . ".log");
*/
    }

    /*---------------------------------
   * 上传完毕后的操作。用于输出xml，并记录上传参数
   * 接收参数:
   * @param string $message输出给flash的文字提示
   * @param string $imgURL图片在服务器的相对地址。
   * @param string $title图片原名称。
   * ----------------------------------
   * */

    private function HandleOk($message) { //上传成功时的记录
        return array(
            'message' => $message,
            'fileName' => $this->file_name,
            'saveName' => $this->file_save_name,
            'savePath' => $this->file_save_path,
            'title' => $this->pic_title,
        );
/*
//        $xml = "<?xml version='1.0' encoding='utf-8'?>";
//        $xml .= "<root><information errorId='0' message='" . $message . "'url='" . $imgURL . "'  title='" . $this->files[$this->upload_name]['name'] . "' /></root>";
//        echo $xml;
//        //下面为日志记录
//        $log = date("[H:i:s]");
//        $log .= "【IP:】" . $this->getIP();
//        $log .= "【用户名:】" . $this->getUser();
//        $log .= "【Session:】" . $this->session;
//        //$log .= "【Cookie:】" . $this->params['cookie'] ;
//        $log .= "【图片字节数:】" . $this->files[$this->upload_name]['size'];
//        $log .= "【原图片宽高:】" . $this->width . "*" . $this->height;
//        $log .= "【AllowComment:】" . $this->params['allowComment'];
//        $log .= "【AllowReprint:】" . $this->params['allowReprint'];
//        $log .= "【Rotaion:】" . $this->params['rotation'] . "\r\n";
//        error_log($log, 3, "./" . $this->log_path . "/oklog" . date("[Y-m-d]") . ".log");
*/
    }

    /*----------------------------------
   * 返回访问者的IP
   * @return IP
   * ---------------------------
   * */
    private function getIP() { //获取上传者IP
        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) {
            $ip = getenv("HTTP_CLIENT_IP");
        } else
            if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) {
                $ip = getenv("HTTP_X_FORWARDED_FOR");
            } else
                if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) {
                    $ip = getenv("REMOTE_ADDR");
                } else
                    if (isset ($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) {
                        $ip = $_SERVER['REMOTE_ADDR'];
                    } else {
                        $ip = "unknown";
                    }
        return ($ip);
    }

    private function getUser() {
//这里添加获取上传者的网站登陆ID
    }

} //class
?>