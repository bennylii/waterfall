/**
 * Created with JetBrains PhpStorm.
 * User: SPRINGWANG
 * Date: 13-3-28
 * Time: 下午11:37
 * To change this template use File | Settings | File Templates.
 */


define(function(require, exports, module){

    var APPCache = require('./cache');

    function Waterfall(options){
        this.setOptions(options);

        this.ckey = '';
        this.cache = new APPCache();
        // 滚动绑定
        $(window).bind('scroll', this.onScroll.bind(this));
    }

    module.exports = Waterfall;

    Waterfall.prototype.setOptions = function(options){
        this.options = {
            page:1,
            isEnd: false,
            apiURL: '?m=Picture&a=getList',
            topURL: '?m=Picture&a=setTopShow',
            size: 230,
            autoResize: true,
            container: $('#tiles'),
            offset: 5,
            loading: $('#loaderCircle')
        };
        this.options = $.extend(this.options,options || {});
        this.options.itemWidth = this.options.size+10;

        this.handler = null;
        this.page = this.options.page;
        this.isEnd = this.options.isEnd;
        this.isLoading = false;

        return this;
    };

    Waterfall.prototype.onScroll = function(event) {
        if(!this.isLoading) {
            var closeToBottom = ($(window).scrollTop() + $(window).height() > $(document).height() - 100);
            if(closeToBottom) {
                this.loadData();
            }
        }
        return this;
    };

    Waterfall.prototype.applyLayout = function() {
    //        console.log(this.options);
        this.handler = $('li',this.options.container);
        this.handler.wookmark(this.options);
        return this;
    };


    Waterfall.prototype.loadData = function() {
        if(this.isEnd) return false;
        this.isLoading = true;
        this.options.loading.show();
        var url = this.options.apiURL+'&p='+this.page;
        this.ckey = $.md5(url);
        var cval =  this.cache.get(this.ckey);
    //    console.log(this.cache);
        if(cval){
            this.onLoadData(cval);
        } else{
            $.ajax({
                url: url,
                dataType: 'json',
                success: this.onLoadData.bind(this)
            });
        }
        return this;
    };

    Waterfall.prototype.onLoadData = function(rsp) {
    //    console.log(this.ckey) ;
        this.cache.set(this.ckey,rsp);
    //        console.log(this.options);
        var data = rsp.data.list;

        this.isLoading = false;
        this.options.loading.hide();

        if(rsp.data.isEnd) return false;

        this.isEnd =  rsp.data.isEnd; //所有数据加载完了


        this.page++;

        var html = [],i=0, length=data.length, image,topShow;

        for(; i<length; i++) {
            image = data[i];
            topShow = image.top_show == 1 ? '取消': '置顶';
            html.push('<li rel="lbl">'+
                '<img rel="lbi" src="/uploads/'+image.path+'_'+this.options.size+'" data-src="/uploads/'+image.path+'" width="'+this.options.size+'" height="'+Math.round(image.height/image.width*this.options.size)+'" title="'+image.title+'" >'+
                '<p>'+image.title+'</p>'+
                '<a class="topShow" data-init="0" data-id="'+image.id+'" data-top="'+-image.top_show+'">'+topShow+'</a>'+
                '</li>');
        }

        this.options.container.append(html.join(''));

        this.applyLayout();
        this.setTopShow($("a.topShow",this.options.container));

        return this;
    };

    Waterfall.prototype.setTopShow = function($els){
    //    console.log($els);
        var inst = this;
        //$els.attr('data-init',1);
        $els.unbind('click').click(function(){
            var el = $(this);
            $.ajax({
                url: inst.options.topURL,
                dataType: 'json',
                data: {pid: el.attr('data-id'),top: el.attr('data-top')},
                success: function(rsp){
                    if(rsp.status == 1){
                        el.attr('data-top',-rsp.data.top_show);
                        el.html(rsp.data.top_show == 1 ? '取消': '置顶');
                    } else{
                        alert('置顶错误，请重试，或者联系QQ: 184412679');
                    }
                }
            });
        });
        return this;
    }

    /**
     * Refreshes the layout.
     */
    Waterfall.prototype.reset = function(options) {
        this.options.container.html('');
        this.setOptions(options);
        this.loadData();
        return this;
    };

    Waterfall.prototype.clear = function(options) {
        this.options.container.html('');
        this.setOptions({
            page:1,
            isEnd: false}
        );
        return this;
    };


});