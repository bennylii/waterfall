<?php
/**
 * Created by PhpStorm.
 * User: NICK
 * Date: 2011-7-9
 * Time: 13:48:47
 * JUZITAI.COM 2011-2012 
 */
 
class CommonModel extends Model{


    // 是否使用缓存，默认使用

    function __construct(){
		parent::__construct();
    }

    /**
     * 获取指定条件的列表数据
     * @param string $field 要取什么字段
     * @param string $where 查询条件
     * @param string $order 排序，默认为id倒序
     * @param int $limit 取多少条
     * @param int $page 当前第几页
     * @return mixed
     */
    public function getList($where='1',$field='',$order='',$limit=10000,$page = 1){
        if(empty($field)) $field = '*';
        if(empty($limit)) $limit=10000;

        if(empty($order)) $val = $this->field($field)->where($where)->page($page.','.$limit)->select();
        else $val = $this->field($field)->where($where)->order($order)->page($page.','.$limit)->select();

        return $val;
    }


    /**
     * 获取一条记录的某一个字段的值
     * @param  $id  记录ID
     * @param string $field 字段名称
     * @return Mix
     */
    public function getOne($id = '',$field = 'id'){
        if(empty($id)) return false;
        $result = $this->find($id);
        if(!empty($result)){
            if(isset($result[$field])) return $result[$field];
        }
        return false;
    }
}
   
?>
