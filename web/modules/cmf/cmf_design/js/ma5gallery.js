/*
*   MA5Gallery
*   v 1.6
*   Copyright (c) 2015 Tomasz Kalinowski
*   http://galeria.ma5.pl
*   GitHub: https://github.com/ma-5/ma5-gallery
*/ 
;
jQuery.fn.ma5preload = function() {
    jQuery('body').append('<div class="ma5-preloadbox"></div>');
    this.each(function(){
        if (typeof jQuery(this).data('ma5pathtofull') !== "undefined") {
            jQuery(this).clone().attr('src', jQuery(this).data('ma5pathtofull')).appendTo('.ma5-preloadbox');
        } else {
            jQuery(this).clone().attr('src', jQuery(this).attr('src').replace(/\-thumbnail./, '.')).appendTo('.ma5-preloadbox');
        }
    });  
}
function ma5showActive() {
    jQuery('.ma5-imgbox').addClass('ma5-previous');
    setTimeout(function() {jQuery('.ma5-imgbox.ma5-previous').remove();jQuery('body').removeClass('ma5-in');}, 1000);
    if (typeof jQuery('.ma5-active img').data('ma5pathtofull') !== "undefined") {
        var ma5clone = jQuery('.ma5-active img').clone().attr('src', jQuery('.ma5-active img').data('ma5pathtofull')).addClass('ma5-clone');
    } else {
        var ma5clone = jQuery('.ma5-active img').clone().attr('src', jQuery('.ma5-active img').attr('src').replace(/\-thumbnail./, '.')).addClass('ma5-clone');
    };
    jQuery('body').addClass('ma5-in').append('<div class="ma5-imgbox"></div>');
    ma5showFigcaption();
    ma5hideFigcaption();
    jQuery(ma5clone).appendTo(jQuery('.ma5-imgbox').last());
}
function ma5exit() {
    jQuery('figure').removeClass('ma5-active'); 
    jQuery('.ma5-imgbox').addClass('ma5-out');
    jQuery('.ma5-tmp').addClass('ma5-out');
    jQuery('.ma5-prev, .ma5-next').remove();
    jQuery('.ma5-figcaption').addClass('ma5-out');
    setTimeout(function() { jQuery('.ma5-tmp').remove(); jQuery('.ma5-imgbox').remove(); jQuery('body').removeClass('ma5-gallery-active'); jQuery('.ma5-figcaption').remove()}, 800);
}
function ma5hideActive() {
    jQuery('.ma5-imgbox').on('touch click', function() {
        ma5exit();
    });
}
function ma5goPrev() {
    if(jQuery('.ma5-tmp .ma5-active').prev().length) {
        jQuery('.ma5-tmp .ma5-active').removeClass('ma5-active').prev().addClass('ma5-active');
        ma5showActive();
        ma5hideActive();
    }
}
function ma5goNext() {
    if(jQuery('.ma5-tmp .ma5-active').next().length) {
        jQuery('.ma5-tmp .ma5-active').removeClass('ma5-active').next().addClass('ma5-active');
        ma5showActive();
        ma5hideActive();
    }
}
function ma5showFigcaption() {
    if( jQuery('.ma5-active').has('figcaption').length ) {
        jQuery('.ma5-figcaption').removeClass('ma5-figcaption').addClass('ma5-figcaption-old');
        jQuery('body').append('<div class="ma5-figcaption"><div class="ma5-centerbox"></div></div>');
        jQuery('.ma5-imgbox').last().addClass('ma5-has-figcaption');
        jQuery('.ma5-active figcaption').contents().clone().appendTo(jQuery('.ma5-figcaption .ma5-centerbox'));
    } else {
        jQuery('.ma5-figcaption').addClass('ma5-out');
        setTimeout(function() {jQuery('.ma5-figcaption').remove()}, 800);
    }
}
function ma5hideFigcaption() {
    setTimeout(function() {jQuery('.ma5-figcaption-old').remove()}, 800);
}
jQuery.fn.ma5gallery = function(atributes) {
    if(atributes.preload == true) {
        jQuery(this).ma5preload();
    };    
    var thisSelector = '.ma5-tmp '+this.selector;
    jQuery(this).on('touch click', function(event) {
        if(!jQuery('.ma5-imgbox').hasClass('ma5-out') && !jQuery('body').hasClass('ma5-in') ) {
            if(jQuery(this).parent().parent().hasClass('ma5-gallery') || jQuery(this).parent().parent().hasClass('ma5-bg')) {
                // gallery mode
                jQuery(this).parent().addClass('ma5-active').parent().clone().appendTo(jQuery('body')).removeClass('ma5-gallery').addClass('ma5-tmp');
                jQuery('.ma5-gallery .ma5-active').removeClass('ma5-active');
                if (!jQuery('body').hasClass('ma5-gallery-active')) {
                    jQuery('body').append('<div class="ma5-prev"></div><div class="ma5-next"></div>');
                    jQuery('body').addClass('ma5-gallery-active');
                    jQuery('.ma5-tmp').find('figure').wrapAll( '<div class="ma5-bg" />');
                    setTimeout(function() {jQuery('.ma5-tmp').addClass('ma5-control');}, 800);
                    ma5showActive();
                    ma5hideActive();
                }
                jQuery('.ma5-prev').on('touch click', function() {
                    if(jQuery("body").hasClass('ma5-gallery-active') && !jQuery("body").hasClass('ma5-in')) {
                        ma5goPrev();
                    };
                });
                jQuery('.ma5-next').on('touch click', function() {
                    if(jQuery("body").hasClass('ma5-gallery-active') && !jQuery("body").hasClass('ma5-in')) {
                        ma5goNext();
                    }
                });
                jQuery(thisSelector).on('touch click', function() {
                    if(!jQuery(this).parent().hasClass('ma5-active') && !jQuery("body").hasClass('ma5-in') ) {
                        jQuery('.ma5-tmp figure').removeClass('ma5-active');
                        jQuery(this).parent().addClass('ma5-active');
                        ma5showActive();
                        ma5hideActive(); 
                    }
                });
            } else {
                //single mode
                jQuery(this).parent().addClass('ma5-active');
                ma5showActive();
                ma5hideActive();
            }
        }
    });
    //key navigate
    jQuery("body").keydown(function(e) {
        if(jQuery("body").hasClass('ma5-gallery-active') && !jQuery("body").hasClass('ma5-in')) {
            if(e.keyCode == 37) {ma5goPrev()} else if(e.keyCode == 39) {ma5goNext()} 
        }
        if(e.keyCode == 27) {ma5exit()}
    });
};
