<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$

class StaticPage extends Think {
    // 起始行数
    public $firstRow	;
    // 列表每页显示行数
    public $listRows	;
    // 手动设置path部分
    public $path  ;
    // 页数跳转时要带的参数
    public $parameter  ;
    // 分页总页面数
    protected $totalPages  ;
    // 总行数
    protected $totalRows  ;
    // 当前页数
    protected $nowPage    ;
    // 分页的栏的总页数
    protected $coolPages   ;
    // 分页栏每页显示的页数
    protected $rollPage   ;
	// 分页显示定制
    protected $config  =	array('header'=>'条记录','prev'=>'上一页','next'=>'下一页','theme'=>' <span class="rowTotal">%totalRow% %header%</span>%prePage% %linkPage% %nextPage%');

    /**
     +----------------------------------------------------------
     * 架构函数
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param array $totalRows  总的记录数
     * @param array $listRows  每页显示记录数
     * @param array $parameter  分页跳转的参数
     +----------------------------------------------------------
     */
    public function __construct($totalRows,$listRows,$path ='',$parameter='') {
        $this->totalRows = $totalRows;
        $this->path = $path;
        $this->parameter = $parameter;
        $this->rollPage = C('PAGE_ROLLPAGE');
        $this->listRows = !empty($listRows)?$listRows:C('PAGE_LISTROWS');
        $this->totalPages = ceil($this->totalRows/$this->listRows);     //总页数
        $this->coolPages  = ceil($this->totalPages/$this->rollPage);
        $this->nowPage  = !empty($_GET[C('VAR_PAGE')])?$_GET[C('VAR_PAGE')]:1;
        if(!empty($this->totalPages) && $this->nowPage>$this->totalPages) {
            $this->nowPage = $this->totalPages;
        }
        $this->firstRow = $this->listRows*($this->nowPage-1);
    }

    public function setConfig($name,$value) {
        if(isset($this->config[$name])) {
            $this->config[$name]    =   $value;
        }
    }

    /**
     +----------------------------------------------------------
     * 分页显示输出
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     */
    public function show() {
        if(0 == $this->totalRows) return '';
        $url  =  $_SERVER['REQUEST_URI'];
        $parse = parse_url($url);
        $url= 'http://'.$_SERVER['HTTP_HOST'];
        $lp= '';
        if(!empty($this->path)) $path = $this->path;
        else $path = $parse['path'];
        if(preg_match('/\/$/',$path)){
            $lp=substr($path,-1);
            $path = substr($path,0,-1);
        }
        $path = preg_replace('/-[\d]+$/','',$path).'-';
        $url = $url.$path;
        $query = $lp.'';
        if(isset($parse['query'])) {
            $query = $lp.'?'.$parse['query'];
        }
        //上下翻页字符串
        $prevPage   = $this->nowPage-1;
        $nextPage = $this->nowPage+1;
        if ($prevPage>0){
            $prevLinkPage="<a class=\"pageUp\" href='".$url.$prevPage.$query."'>".$this->config['prev']."</a>";
        }else{
            $prevLinkPage="";
        }
        if ($nextPage <= $this->totalPages){
            $downLinkPage="<a class=\"pageDown\" href='".$url.$nextPage.$query."'>".$this->config['next']."</a>";
        }else{
            $downLinkPage="";
        }

        $linkPage = "";
        if($this->totalPages <= 5){
            $linkPage .= $this->buildLinkPage($url,$query,1,$this->totalPages,$this->nowPage);
        } else{
            if($this->nowPage <=5){
                $linkPage .= $this->buildLinkPage($url,$query,1,$this->nowPage,$this->nowPage);
            } else{
                $linkPage .= "<a class=\"pageLink\" href='".$url.'1'.$query."'>1</a>";
                $linkPage .= '...';
                $linkPage .= $this->buildLinkPage($url,$query,$this->nowPage-3,$this->nowPage,$this->nowPage);
            }

            if(($this->totalPages - $this->nowPage) > 5){
                $linkPage .= $this->buildLinkPage($url,$query,$this->nowPage+1,$this->nowPage+4,$this->nowPage);
                $linkPage .= '...';
                $linkPage .= "<a class=\"pageLink\" href='".$url.$this->totalPages.$query."'>".$this->totalPages."</a>";
            } else{
                $linkPage .= $this->buildLinkPage($url,$query,$this->nowPage+1,$this->totalPages,$this->nowPage);
            }
        }

        $pageStr	 =	 str_replace(
            array('%header%','%totalRow%','%prePage%','%linkPage%','%nextPage%'),
            array($this->config['header'],$this->totalRows,$prevLinkPage,$linkPage,$downLinkPage),$this->config['theme']);
        return $pageStr;
    }

    private function buildLinkPage($url,$query,$start,$end,$now){
        $linkPage = '';
        for($i=$start;$i<=$end;$i++){
            if($i != $now){
                $linkPage .= "<a class=\"pageLink\" href='".$url.$i.$query."'>".$i."</a>";
            }else{
                $linkPage .= "<span class='current'>".$i."</span>";
            }
        }
        return $linkPage;
    }

}
?>