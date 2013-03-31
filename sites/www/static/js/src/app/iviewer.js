/**
 * Created with JetBrains PhpStorm.
 * User: SPRINGWANG
 * Date: 13-3-28
 * Time: 下午11:37
 * To change this template use File | Settings | File Templates.
 */


define(function(require, exports, module){

    function Iviewer(options){
        this.setOptions(options).initEvents().build();
        this.curImage = null;
    }

    module.exports = Iviewer;

    Iviewer.prototype.setOptions = function(options){
        this.options = {
            loadingIcon: 'static/images/loading.gif',
            closeBtnIcon: 'static/images/close.png',
            resizeDuration: 700,
            fadeDuration:500
        };
        this.options = $.extend(this.options,options || {});
        return this;
    };

    Iviewer.prototype.initEvents = function(){
        $('body').on('click', 'img[rel=lbi]', function(e) {
            this.start($(e.currentTarget));
            return false;
        }.bind(this));
        $(window).on("resize", this.sizeOverlay);
        return this;
    }

    Iviewer.prototype.build = function() {

        var str =   '<div id="iviewerOverlay"></div>'+
                        '<div id="iviewer">'+
                            '<div class="lb-outerContainer">'+
                                '<div class="lb-container">'+
                                    '<img class="lb-image" style="display: inline;"/>'+
                                    '<div class="lb-nav" style="display: block;">'+
                                        '<a class="lb-prev" style="display: none;"></a>'+
                                        '<a class="lb-next" style="display: none;"></a>'+
                                    '</div>'+
                                    '<div class="lb-loader" style="display: none;">'+
                                        '<a class="lb-cancel"><img src="'+this.options.loadingIcon+'"></a>'+
                                    '</div> '+
                                '</div>'+
                            '</div>'+
                            '<div class="lb-dataContainer">'+
                                '<div class="lb-data">'+
                                    '<div class="lb-details">'+
                                        '<span class="lb-caption" style="display: inline;"></span>'+
                                        '<span class="lb-number" style=""></span>'+
                                    '</div>'+
                                    '<div class="lb-closeContainer"> '+
                                        '<a class="lb-close"><img src="'+this.options.closeBtnIcon+'"></a>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</div>';


        var iviewer;

        $('body').append(str);

        $('#iviewerOverlay').hide().on('click', function(e) {
            this.end();
            return false;
        }.bind(this));
        iviewer = $('#iviewer');
        iviewer.hide().on('click', function(e) {
            if ($(e.target).attr('id') === 'iviewer') this.end();
            return false;
        }.bind(this));
        iviewer.find('.lb-outerContainer').on('click', function(e) {
            if ($(e.target).attr('id') === 'iviewer') this.end();
            return false;
        }.bind(this));
        iviewer.find('.lb-prev').on('click', function(e) {
            this.prevImage();
            return false;
        }.bind(this));
        iviewer.find('.lb-next').on('click', function(e) {
            this.nextImage();
            return false;
        }.bind(this));
        iviewer.find('.lb-loader, .lb-close').on('click', function(e) {
            this.end();
            return false;
        }.bind(this));
        return this;
    };

    Iviewer.prototype.start = function(img) {
        var iviewer, win, left, top ;

        this.switchImage(img);

        $('#iviewerOverlay').width($(document).width()).height($(document).height()).fadeIn(this.options.fadeDuration);

        win = $(window);
        top = win.scrollTop() + win.height() / 10;
        left = win.scrollLeft();
        iviewer = $('#iviewer');
        iviewer.css({
            top: top + 'px',
            left: left + 'px'
        }).fadeIn(this.options.fadeDuration);

        return this;
    };

    Iviewer.prototype.prevImage = function() {
        var curLi = this.curImage.parents('[rel=lbl]'),
            prevLi = curLi.prev('[rel=lbl]');

        if(prevLi.length = 1){
            this.switchImage(prevLi.children('img[rel=lbi]'));
        }
    };

    Iviewer.prototype.nextImage = function() {
        var curLi = this.curImage.parents('[rel=lbl]'),
            nextLi = curLi.next('[rel=lbl]');

        if(nextLi.length = 1){
            this.switchImage(nextLi.children('img[rel=lbi]'));
        }
    };

    Iviewer.prototype.switchImage = function(img) {
        this.curImage = img,src = img.attr('data-src');

        var iviewer = $('#iviewer'),
            image = iviewer.find('img.lb-image');

        this.sizeOverlay();
        this.disableKeyboardNav();

        iviewer.find('.loader').fadeIn(this.options.fadeDuration);

        iviewer.find('.lb-image, .lb-nav, .lb-prev, .lb-next, .lb-dataContainer, .lb-numbers, .lb-caption').hide();
        iviewer.find('.lb-outerContainer').addClass('animating');

        preloader = new Image;
        preloader.onload = function() {
            image.attr('src', src);
            image.width = preloader.width;
            image.height = preloader.height;
            return this.sizeContainer(preloader.width, preloader.height);
        }.bind(this);
        preloader.src = src;
        this.updateNav();

    };

    Iviewer.prototype.updateNav = function() {
        var curLi = this.curImage.parents('[rel=lbl]');

        if(curLi.next('[rel=lbl]').length == 0){
            $('a.lb-next').hide();
        } else{
            $('a.lb-next').show();
        };

        if(curLi.prev('[rel=lbl]').length == 0){
            $('a.lb-prev').hide();
        } else{
            $('a.lb-prev').show();
        };
    };

    Iviewer.prototype.sizeContainer = function(imageWidth, imageHeight) {
        var container, iviewer, outerContainer, containerBottomPadding,
            containerLeftPadding, containerRightPadding, containerTopPadding,
            newHeight, newWidth, oldHeight, oldWidth;
        iviewer = $('#iviewer');
        outerContainer = iviewer.find('.lb-outerContainer');
        oldWidth = outerContainer.outerWidth();
        oldHeight = outerContainer.outerHeight();
        container = iviewer.find('.lb-container');
        containerTopPadding = parseInt(container.css('padding-top'), 10);
        containerRightPadding = parseInt(container.css('padding-right'), 10);
        containerBottomPadding = parseInt(container.css('padding-bottom'), 10);
        containerLeftPadding = parseInt(container.css('padding-left'), 10);
        newWidth = imageWidth + containerLeftPadding + containerRightPadding;
        newHeight = imageHeight + containerTopPadding + containerBottomPadding;
        if (newWidth !== oldWidth && newHeight !== oldHeight) {
            outerContainer.animate({
                width: newWidth,
                height: newHeight
            }, this.options.resizeDuration, 'swing');
        } else if (newWidth !== oldWidth) {
            outerContainer.animate({
                width: newWidth
            }, this.options.resizeDuration, 'swing');
        } else if (newHeight !== oldHeight) {
            outerContainer.animate({
                height: newHeight
            }, this.options.resizeDuration, 'swing');
        }
        setTimeout(function() {
            iviewer.find('.lb-dataContainer').width(newWidth);
            iviewer.find('.lb-prevLink').height(newHeight);
            iviewer.find('.lb-nextLink').height(newHeight);
            this.showImage();
        }.bind(this), this.options.resizeDuration);
    };

    Iviewer.prototype.showImage = function() {
        var iviewer;
        iviewer = $('#iviewer');
        iviewer.find('.lb-loader').hide();
        iviewer.find('.lb-image').fadeIn('slow');
        iviewer.find('.lb-nav, .lb-dataContainer, .lb-caption').fadeIn('slow');
        iviewer.find('.lb-caption').html(this.curImage.attr('alt') || this.curImage.attr('title'));

        this.updateNav();
        this.enableKeyboardNav();
    };

    Iviewer.prototype.sizeOverlay = function() {
        $('#iviewerOverlay').width($(document).width()).height($(document).height());
        return this;
    };

    Iviewer.prototype.enableKeyboardNav = function() {
        $(document).on('keyup.keyboard', $.proxy(this.keyboardAction, this));
    };

    Iviewer.prototype.disableKeyboardNav = function() {
        $(document).off('.keyboard');
    };

    Iviewer.prototype.keyboardAction = function(event) {
        var KEYCODE_ESC = 27, KEYCODE_LEFTARROW = 37, KEYCODE_RIGHTARROW = 39, key, keycode;
        keycode = event.keyCode;
        key = String.fromCharCode(keycode).toLowerCase();
        if (keycode === KEYCODE_ESC || key.match(/x|o|c/)) {
            this.end();
        } else if (key === 'p' || keycode === KEYCODE_LEFTARROW) {
            this.prevImage();
        } else if (key === 'n' || keycode === KEYCODE_RIGHTARROW) {
            this.nextImage();
        }
    };

    Iviewer.prototype.end = function() {
        this.disableKeyboardNav();
        $(window).off("resize", this.sizeOverlay);
        $('#iviewer').fadeOut(this.options.fadeDuration);
        $('#iviewerOverlay').fadeOut(this.options.fadeDuration);
    };

    Iviewer.init = function(){
        return new Iviewer();
    }

});