<?php
require_once  CONFIG_PATH.'db.php';

return array_merge($dbConfig,array(

	//'配置项'=>'配置值'
	//设置静态
    'TMPL_L_DELIM'          => '<!--{',	                // 模板引擎普通标签开始标记
    'TMPL_R_DELIM'          => '}-->',	                // 模板引擎普通标签结束标记
	'HTML_CACHE_ON'			=> false,                   // 默认关闭静态缓存
    'HTML_CACHE_TIME'       => 3600,
    'HTML_READ_TYPE'        => 0,
    'DB_FIELDS_CACHE'       => true,
    'APP_DEBUG'				=> true,	                // 是否开启调试模式
    'TOKEN_ON'              => false,                    // 开启令牌验证
    'PAGE_ROLLPAGE'         => 10,
));
?>