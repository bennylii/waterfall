<?php
/**
 * Created by PhpStorm.
 * User: NICK
 * Date: 2011-7-9
 * Time: 13:48:47
 * JUZITAI.COM 2011-2012 
 */
 
class CommonAction extends Action{

    public $iM;


    function __construct(){
		parent::__construct();

//        if(MODULE_NAME != 'Index') $this->iM = D(MODULE_NAME);

        $this->quoteRequestData();
        $this->assignGlobalData();
    }

    /**
     * 转换请求数据，防止sql注入
     */
    private function quoteRequestData(){
        if (!get_magic_quotes_gpc()){
            foreach($_POST as $key => $value){
                $_POST[$key] = addslashes($value);
            }
            foreach($_GET as $key => $value){
                $_GET[$key] = addslashes($value);
            }
        }
    }

    /**
     * 对一些全局变量需要在模版中调用的在这里统一做映射
     * @return void
     */
    private function assignGlobalData(){

        global $DOMAIN_LIST;

        $this->assign('DOMAIN_LIST',$DOMAIN_LIST);
        $this->assign('DOMAIN',$DOMAIN_LIST['www']);
        $this->assign('VERSION_JS',VERSION_JS);
        $this->assign('VERSION_CSS',VERSION_CSS);
        $this->assign('MODULE_NAME',MODULE_NAME);
        $this->assign('ACTION_NAME',ACTION_NAME);
        $this->assign('PAGEKEY',strtoupper(MODULE_NAME.'_'.ACTION_NAME));
    }


}
   
?>
