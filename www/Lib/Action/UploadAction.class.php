<?php
/**
 * Created by JetBrains PhpStorm.
 * User: SPRINGWANG
 * Date: 13-3-28
 * Time: 上午9:19
 * To change this template use File | Settings | File Templates.
 */

class UploadAction extends Action{

    private $pM;

    public function __construct(){
        parent::__construct();

        $this->pM = D('Picture');

        // 加载上传和缩略图处理类
        import('SwfUpload', __ROOT__.'include/Lib/');
        import('Thumbnail', __ROOT__.'include/Lib/');
    }

    public function test(){
        $this->display(PUBLIC_TMPL_PATH.'public/upload.html');
    }

    public function index(){
        $u = new SwfUpload();
        $t = new Thumbnail();

        $u->files=$_FILES;         //设置file数组。（只能单张）

        //请记住先检测，再上传
        $r = $u->check();
        if($r === true && $r = $u->upload()){
//            echo_log($r);
            if(!isset($r['error'])){
                // 生成200的缩略图
                $t->setSrcImg($r['savePath']);
                $height = $t->getImgHeight();
                $width = $t->getImgWidth();

                if($this->savePicture($r['saveName'],$r['title'],$width,$height)){
                    // 生成小的缩略图
                    $t->setDstImg($r['savePath'].'_'.THUMBNAIL_WIDTH_SMALL);
                    $t->createImg(THUMBNAIL_WIDTH_SMALL);

                    // 生成打的缩略图
                    $t->setSrcImg($r['savePath']);
                    $t->setDstImg($r['savePath'].'_'.THUMBNAIL_WIDTH_LARGE);
                    $t->createImg(THUMBNAIL_WIDTH_LARGE);

                    $rt = array('thumbUrl' => $r['saveName'] ,'title' => $r['title'],'width' => $width, 'height' => $height);

                    json_success($rt);
                } else{
                    json_failure('图片信息入库异常');
                }
            } else{
                json_failure($r['error']);
            }
        } else{
            json_failure($r['error']);
        }
    }

    public function savePicture($picSaveName,$picTitle,$width,$height){
        $data = array(
            'title' => $picTitle,
            'path' =>  $picSaveName,
            'width' => $width,
            'height' => $height
        );

        if (false === ($data = $this->pM->create ($data)))
            return false;

        $id = $this->pM->add($data);

        if(empty($id)) return false;

        return true;
    }
}

?>