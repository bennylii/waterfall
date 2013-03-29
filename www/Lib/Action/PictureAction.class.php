<?php
/**
 * Created by JetBrains PhpStorm.
 * User: springwang
 * Date: 12-3-7
 * Time: 下午12:05
 * To change this template use File | Settings | File Templates.
 */

class PictureAction extends CommonAction{
    private $pM;

    public function __construct(){
        parent::__construct();

        $this->pM = D('Picture');
    }

    /**
     * 图片列表页
     */
    public function getList(){
        $list = $this->pM->getListByDesc(LIST_PPN,$p = get_query('p',1));
        json_success(array('isEnd' => empty($list),'list' =>$list));
    }

    /**
     * 图片列表页
     */
    public function getTopList(){
        $list = $this->pM->getTopListByDesc(LIST_PPN,get_query('p',1));
        json_success(array('isEnd' => empty($list),'list' =>$list));
    }

    /**
     * 置顶展示设置
     */
    public function setTopShow(){
        $pid = intval(get_query('pid',0));
        $top = intval(get_query('top'));
        if(is_int($pid) && $pid > 0 && ($top == 1 || $top == -1)){
            $data =  array('id' => $pid,'top_show' => $top,'updatetime' => time());
            $r = $this->pM->save($data);
            if($r) json_success($data);
            json_failure($data);
        }
    }

    /**
     * 搜索需求
     */
    public function search(){
        $key = get_query('key');
        if(empty($key)) this.getList();

        $list = $this->pM->search($key,LIST_PPN,get_query('p',1));
        json_success(array('isEnd' => empty($list),'list' =>$list));
    }
}


?>
