define(function(require, exports, module){

    require('../lib/lib');
    require('./core');
    require('./swfupload.handlers');

    var APPCache = require('./cache');
    var Waterfall = require('./waterfall');
    var Iviewer = require('./iviewer');

	function init(){
        // 初始化瀑布流组件
		var waterfall = new Waterfall().loadData();
		initSwitchThumbSize(waterfall); // 初始化大小图切换组件
		initSearch(waterfall);  // 初始化搜索
		initSlider();       // 初始化顶部幻灯片
		initSWFUpload();    // 初始化上传组件
        Iviewer.init();     // 初始化图片浏览组件
	}
	
	module.exports = init;
	
    function initSlider(){
        $.ajax({
            url: '?m=Picture&a=getTopList',
            dataType: 'json',
            success: function(rsp) {
                var data = rsp.data.list, html = [],i=0, length=data.length, image;

                for(; i<length; i++) {
                    image = data[i];
                    html.push('<div rel="lbl"><img rel="lbi" src="/uploads/'+image.path +'_230" data-src="/uploads/'+image.path +'" width="230"></div>');
                }

                $('#jsCarousel').append(html.join('')).jsCarousel({autoscroll: true });
            }
        });
    }

    function initSwitchThumbSize(waterfall){
        $.tabs({
            selector:"ul.white",
            tabsSelector:"li",
            selectedClass:"active",
            click:function(index,instance){
//                console.log($(this).attr('tab-key'));
                waterfall.reset({size:parseInt($(this).attr('tab-key')),page:1,isEnd: false});
            }
        });
    }

    function initSearch(waterfall){
        var url,ckey,cval,cache =new APPCache();
        $('input.searchInput').keyup(function(){
            url = '?m=Picture&a=search&key='+$(this).val();
            ckey = $.md5(url);
//            console.log(appcache.caches);
            if(cval = cache.get(ckey)){
                waterfall.clear().onLoadData(cval);
            } else{
                $.ajax({
                    url: url,
                    dataType: 'json',
                    success: function(rsp) {
                        waterfall.clear().onLoadData(rsp);
                        cache.set(ckey,rsp);
                    }
                });
            }
        })
    }

});

