<?php
/**
 * Created by JetBrains PhpStorm.
 * User: springwang
 * Date: 12-3-7
 * Time: 下午12:05
 * To change this template use File | Settings | File Templates.
 */

class PictureModel extends CommonModel{

    protected $tableName = 'picture';

    public function __construct(){
        parent::__construct();
    }

    // 自动验证字段
    protected $_validate = array(
        array('path','require','照片保存路径必须'),
        array('width','require','照片宽度必须'),
        array('height','require','照片高度必须'),
    );

    // 自动填充字段
    protected $_auto =	array(
        array('createtime','time',self::MODEL_INSERT,'function'),
        array('updatetime','time',self::MODEL_INSERT,'function'),
        array('updatetime','time',self::MODEL_UPDATE,'function'),
    );

    public function getListByDesc($limit,$page){
        return $this->getList(1,'*','id DESC',$limit,$page);
    }

    public function getTopListByDesc($limit,$page){
        return $this->getList('top_show=1','*','updatetime DESC',$limit,$page);
    }

    public function search($key,$limit,$page){
        return $this->getList("title like '%$key%'",'*','updatetime DESC',$limit,$page);
    }
}


?>
