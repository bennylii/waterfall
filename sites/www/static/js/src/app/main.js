define(function(require, exports, module){

    require('../lib/lib');

    var APPCache = require('./cache');
    var Waterfall = require('./waterfall');

    require('./swfupload.handlers');

	function init(){
		var waterfall = new Waterfall();
		initSwitchThumbSize(waterfall.loadData());
		initSearch(waterfall);

		initSlider();
		initSWFUpload();

		$(document).bind('scroll', waterfall.onScroll.bind(waterfall));
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
                    html.push('<div><img src="/uploads/'+image.path +'_230" width="230"></div>');
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

