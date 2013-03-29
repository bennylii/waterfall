<?php
/**
 * Created by JetBrains PhpStorm.
 * User: springwang
 * Date: 2012-08-20
 * Time: 下午 21:20
 * To change this template use File | Settings | File Templates.
 */
 
class IndexAction extends CommonAction{
    private $pM;
    private $bM;
    private $sM;

    public function __construct(){
        parent::__construct();

//        $this->pM = D('Product');
//        $this->bM = D('Brand');
//        $this->sM = D('Store');
    }

    /**
     * 活动日志列表页
     */
    public function index(){

        $this->display(PUBLIC_TMPL_PATH.'public/index.html');
        // 视图判断
//        if(isset($_GET['tp'])){
//            $pathInfos = parse_url($_SERVER['REQUEST_URI']);
//            $itemStyle =  get_query('tp') == 'grid' ? 'grid'  : 'blog';
//            setcookie('view',$itemStyle,time()+3153600000,'/');
//            header('HTTP/1.1 301 Moved Permanently');
//            header('Location:'.$pathInfos['path']);
//            exit;
//        } else{
//            $itemStyle = get_cookie('view') == 'grid' ? 'grid'  : 'blog';
//        }
//
//        global $ACTIVITY_CATEGORY;
//        $nowHour = strtotime(date('Y-m-d H:i:00'));
//        $nM = D('Notice');
//        // 去掉走秀网活动
//        $where = EXCLUDE_SID." AND status = 1 AND starttime < $nowHour AND endtime > $nowHour";
//        $order = 'starttime DESC,ssort ASC,id ASC';
//
//        $sid = get_query('sid');
//        $category = get_query('category');
//
//        if(!empty($sid)){
//            $where .= " AND sid= $sid ";
//            $store = $this->sM->getStoreById($sid);
//        }
//        if(!empty($category)){
//            if($category == 'new'){ // 今日新上的活动
//                $where .= " AND starttime > ".$this->todayTs;
//            } else if($category == 'hot'){  // 最热的活动
//                $where .= " AND soldouts > ".ACTIVITY_HOT_SOLDOUTS;
//            } else if($category == 'syt'){  // 即将结束的活动
//                $where .= " AND endtime < ".strtotime('+3 day',$this->todayTs);
//                $order = 'endtime ASC,ssort ASC,id ASC';
//            } else if($category == 'exp'){  // 过期的活动
//                $where = " status = 1 AND endtime < $nowHour";
//            } else {
//                $where .= " AND category LIKE '%$category%' ";
//            }
//            $cate4seo = $ACTIVITY_CATEGORY[$category]['seo'];// seo信息
//        }
//
//        $lazyCount = 1; $ppn = ACTIVITY_LIST_BLOG_PPN;
//        if($itemStyle == 'grid'){
//            $lazyCount = 2; $ppn = ACTIVITY_LIST_GRID_PPN;
//        }
//        $todayBrands = $this->bM->getTodayBrands();
//        if(empty($todayBrands)) $yesterdayBrands = $this->bM->getYesterdayBrands();
//
//        $this->showActivityList($ppn,$where,$order); //ACTIVITY_LIST_PPN
//
//
//        $this->assign('onlineStores',$this->sM->getOnlineStores());
//        $this->assign('notices',$nM->getMergerFormatList());
//        $this->assign('noticeCount',$nM->getOnlineNoticeTotal());
//        $this->assign('todayBrands',array_slice($todayBrands,0,BRAND_TODAY_FIRST_COUNT));
//        $this->assign('yesterdayBrands',$yesterdayBrands);
//        $this->assign('hotBrands',$this->bM->getHotBrands(30));
//        $this->assign('brandCates',$this->bM->getOnlineBrandsCategory());
//        $this->assign('soldoutProducts',$this->pM->getSoldoutList(54));
//
//        $this->assign('sid',$sid);
//        $this->assign('store',$store);
//        $this->assign('category',$category);
//        $this->assign('cate4seo',$cate4seo); // seo信息
//        $this->assign('lazyCount',$lazyCount);
//        $this->assign('itemStyle',$itemStyle);
//        $this->assign('itemTplt','activity:list_item_'.$itemStyle);
//
//        $this->display(PUBLIC_TMPL_PATH.'activity/list.html');
    }

//    /**
//     * 获取一个简单的列表
//     */
//    public function simple(){
//        $nowHour = time();
//        $stores = array('136' => array(),'145' => array(),'231' => array());
//            // 去掉走秀网活动
//        $where = "status = 1 AND starttime < $nowHour AND endtime > $nowHour";
//        $where .= " AND starttime > ".$this->todayTs;
//        $order = 'soldouts DESC,id ASC';
//
//        foreach($stores as $sid => $v){
//            $where1 = "$where AND sid= $sid ";
//            $v['store'] = $this->sM->getStoreById($sid);
//            $v['activitys'] = $this->iM->getList($where1,'',$order,get_query('pp',5),1);
//            $stores[$sid] = $v;
//        }
//        $this->assign('list',$stores);
////        echo_log($stores);
//        $this->display(PUBLIC_TMPL_PATH.'activity/list_simple.html');
//    }
//
//    /**
//     * 列表输出
//     * @param int $limit    查询记录限制
//     * @param string $where 查询条件
//     * @param string $order 查询结果排序
//     * @return void
//     */
//    private function showActivityList($limit = PER_PAGE_NUMBER, $where = '1', $order = 'id DESC'){
//        $p = get_query('p',1);
//        import('ORG.Util.StaticPage');
//        $path = ''; if($_SERVER['REQUEST_URI'] == '/' || $_SERVER['REQUEST_URI'] == '/temai/') $path = '/temai---1/';
//        $page = new StaticPage($this->iM->getListCount($where),$limit,$path);
//
//        $this->assign('activitys',$this->iM->getListBlog($p,$limit,$where,$order));
//        $this->assign('page',$page->show());
//        $this->assign('currentPage',$p==0?1:$p);
//    }
//
//    /**
//     * 活动最终页
//     */
//    public function detail(){
//        global $SORT_FIELDS,$SORT_ORDERS;
//
//        $id = get_query('id');
//
//        $activity = $this->iM->getActivityById($id);
////        if(empty($activity)) A()->showError404();
////        echo_log($activity);
//        $where ="aid=$id";
//        $soldout = get_query('soldout','');
//        if(!empty($soldout)){
//            $where .= " AND soldout = 2";
//        }
//        $orderBy = 'pr';
//        $orderValue = 'a';
//        $sort = explode('_',get_query('sort'));
//        if(count($sort) == 2){
//            $orderBy = $sort[0];
//            $orderValue = $sort[1];
//        }
//        if(!empty($orderBy) && isset($SORT_FIELDS[$orderBy])
//            && !empty($orderValue) && isset($SORT_ORDERS[$orderValue])){
//            $order = $SORT_FIELDS[$orderBy].' '.$SORT_ORDERS[$orderValue];
//        }
//        $perpage = get_query('pp',PRODUCT_LIST_PPN);
//        $this->showProductList($perpage,$where,$order);
//
//        $this->assign('ait',$activity);
//        $this->assign('soldout',$soldout);
//        $this->assign('orderBy',$orderBy);
//        $this->assign('orderValue',$orderValue);
//        $this->assign('rait',$this->iM->getRecommendList1($id));
//        $this->display(PUBLIC_TMPL_PATH.'activity/detail.html');
//    }
//
//    /**
//     * 列表输出
//     * @param int $limit    查询记录限制
//     * @param string $where 查询条件
//     * @param string $order 查询结果排序
//     * @return void
//     */
//    private function showProductList($limit = PER_PAGE_NUMBER, $where = '1', $order = 'id ASC'){
//        $p = get_query('p',1);
//        $pid = get_query('pid');
//
//        import('ORG.Util.StaticPage');
//        $totalRows =  $this->pM->getListCount($where);
//        $page = new StaticPage($totalRows,$limit);
//
//        if(!empty($pid)) $where .= " AND id != $pid";
//
//        $products = $this->pM->getRequireList($p,$limit,$where,$order);
//
//        if(!empty($pid)){
//            $sp = $this->pM->getProductById($pid);
//            if(!empty($sp)) array_unshift($products,$sp);
//        }
//
//        $this->assign('totalRows',$totalRows);
//        $this->assign('products',$products);
//        $this->assign('page',$page->show());
//        $this->assign('p',$p==0?1:$p);
//    }



}