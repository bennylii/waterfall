/**
 * Created with JetBrains PhpStorm.
 * User: SPRINGWANG
 * Date: 13-3-29
 * Time: 上午10:06
 * To change this template use File | Settings | File Templates.
 */

define(function(require, exports, module){
    /*------------------Function扩展--------------------------*/
    $.extend(Function.prototype, {
        bind: function() {
            var method = this, _this = arguments[0], args = [];
            for (var i=1, il=arguments.length; i<il; i++) {
                args.push(arguments[i]);
            }
            return function() {
                var thisArgs = args.concat();
                for (var i=0, il=arguments.length; i<il; i++) {
                    thisArgs.push(arguments[i]);
                }
                return method.apply(_this, thisArgs);
            };
        },
        bindEvent: function() {
            var method = this, _this = arguments[0], args = [];
            for (var i=1, il=arguments.length; i<il; i++) {
                args.push(arguments[i]);
            }
            return function(e) {
                var thisArgs = args.concat();
                thisArgs.unshift(e || window.event);
                return method.apply(_this, thisArgs);
            };
        }
    });
});

