/**
 * Created with JetBrains PhpStorm.
 * User: SPRINGWANG
 * Date: 13-3-29
 * Time: 下午2:02
 * To change this template use File | Settings | File Templates.
 */


define(function(require, exports, module){

    // 全局缓存处理类
    function APPCache(){
        this.caches = [];
    }

    module.exports = APPCache;

    APPCache.prototype.set = function(ckey,value){
    //    console.log(ckey,value);
        if(typeof value != 'undefined') this.caches[ckey] = value;
    }

    APPCache.prototype.get = function(ckey){
        var v = this.caches[ckey];
        if(typeof v == 'undefined') return false;
        return v;
    }

});