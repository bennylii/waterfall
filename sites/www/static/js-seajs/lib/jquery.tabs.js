(function($){
    $.tabs = function(obj){
        return (this instanceof $.tabs) ? this.init.apply(this,arguments) : new $.tabs(obj);
    }
    //主动事件 通过编程触发
    //被动事件 由用户的行为触发
    $.tabs.prototype = {
        init:function(obj){
            var that = this;
            //配置属性
            $.extend(this,{
                selectedClass:"",
                tabsSelector:">li",
                panelsSelector:"",
                click:$.noop,
                selected:0
            }, obj || { });

            this.ui = $(obj.selector);
            this.tabs =  this.ui.find(this.tabsSelector);
            this.panels = this.ui.find(this.panelsSelector);

            this.select(this.selected)
            this.tabs.click(function(){
                var index = that.tabs.index(this);
                that._switch.call(that,index)
                that.click.call(this,index,that);
            });
        },
        _switch:function(index){
            this.tabs.removeClass(this.selectedClass).eq(index).addClass(this.selectedClass);
            this.panels.hide().eq(index).show();
        },
        select:function(index,callback){
            index = ~~index;
            this._switch(index);
            callback && callback.call(this.tabs[index],index,this);
        },
        remove:function(index,callback){
            index = ~~index;
            this.tabs.eq(index).remove();
            this.panels.eq(index).remove();
            callback && callback.call(this.tabs[index],index,this);
        }
    }
})(jQuery);