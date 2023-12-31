/* Load jQuery.
------------------------------------------------*/
jQuery(document).ready(function ($) {
  // Mobile menu.
  $('.mobile-menu').click(function () {
    $(this).next('.primary-menu-wrapper').toggleClass('active-menu');
  });
  $('.close-mobile-menu').click(function () {
    $(this).closest('.primary-menu-wrapper').toggleClass('active-menu');
  });

  // Full page search.
  $('.search-icon').click(function () {
    $('.search-box').css('display', 'flex');
    return false;
  });
  $('.search-box-close').click(function () {
    $('.search-box').css('display', 'none');
    return false;
  });

  // Scroll To Top.
  $(window).scroll(function () {
    if ($(this).scrollTop() > 80) {
      $('.scrolltop').css('display', 'flex');
    } else {
      $('.scrolltop').fadeOut('slow');
    }
  });
  $('.scrolltop').click(function () {
    $('html, body').scrollTop(0);
  });

// End document ready.
});

/* Function if device width is more than 767px.
------------------------------------------------*/
jQuery(window).on('load', function () {
  // Add empty space for fixed footer.
  if (jQuery(window).width() > 767) {
    var footerheight = jQuery('#footer').outerHeight(true) + 4;
    jQuery('#last-section').css('height', footerheight);
  }

// end window on load
});


// custom JS start

function bottom_slider_container() {
  var element = document.getElementById("block-views-block-footer-slider-block-1");
  element.classList.add("container");
}
bottom_slider_container()
;
!function(k,v,m,t){"use strict";var y="slick",C="unslick",b=y+"--initialized",e=".slick:not(."+b+")",w=".slick__slider",z=".slick__arrow",_=".b-lazy[data-src]:not(.b-loaded)",x=".media__icon--close",P="is-playing",$="is-paused",A=v.blazy||{};function s(e){var n,s,a=k("> "+w,e).length?k("> "+w,e):k(e),l=k("> "+z,e),i=a.data(y)?k.extend({},m.slick,a.data(y)):k.extend({},m.slick),t=!("array"!==k.type(i.responsive)||!i.responsive.length)&&i.responsive,o=i.appendDots,d="blazy"===i.lazyLoad&&A,c=a.find(".media--player").length,r=a.hasClass(C);if(r||(i.appendDots=o===z?l:o||k(a)),t)for(n in t)Object.prototype.hasOwnProperty.call(t,n)&&t[n].settings!==C&&(t[n].settings=k.extend({},m.slick,p(i),t[n].settings));function u(n){a.find(_).length&&((n=a.find(n?".slide:not(.slick-cloned) "+_:".slick-active "+_)).length||(n=a.find(".slick-cloned "+_)),n.length&&A.init&&A.init.load(n))}function f(){c&&h(),d&&u(!1)}function h(){a.removeClass($);var n=a.find("."+P);n.length&&n.removeClass(P).find(x).click()}function g(){a.addClass($).slick("slickPause")}function p(e){return r?{}:{slide:e.slide,lazyLoad:e.lazyLoad,dotsClass:e.dotsClass,rtl:e.rtl,prevArrow:k(".slick-prev",l),nextArrow:k(".slick-next",l),appendArrows:l,customPaging:function(n,s){var i=n.$slides.eq(s).find("[data-thumb]")||null,t='<img alt="'+v.t(i.find("img").attr("alt"))+'" src="'+i.data("thumb")+'">',t=i.length&&0<e.dotsClass.indexOf("thumbnail")?'<div class="slick-dots__thumbnail">'+t+"</div>":"",s=n.defaults.customPaging(n,s);return t?s.add(t):s}}}a.data(y,i),(i=a.data(y)).randomize&&!a.hasClass("slick-initiliazed")&&a.children().sort(function(){return.5-Math.random()}).each(function(){a.append(this)}),r||a.on("init.sl",function(n,s){o===z&&k(s.$dots).insertAfter(s.$prevArrow);s=a.find(".slick-cloned.slick-active "+_);d&&s.length&&A.init&&A.init.load(s)}),d?a.on("beforeChange.sl",function(){u(!0)}):(s=k(".media",a)).length&&(s.find("[data-src]").length||s.hasClass("b-bg"))&&s.closest(".slide__content").addClass("is-loading"),a.on("setPosition.sl",function(n,s){var i,t;s=(i=s).slideCount<=i.options.slidesToShow,t=s||!1===i.options.arrows,a.attr("id")===i.$slider.attr("id")&&(i.options.centerPadding&&"0"!==i.options.centerPadding||i.$list.css("padding",""),s&&(i.$slideTrack.width()<=i.$slider.width()||k(e).hasClass("slick--thumbnail"))&&i.$slideTrack.css({left:"",transform:""}),(i=a.find(".b-loaded ~ .b-loader")).length&&i.remove(),l.length&&k.each(["next","prev"],function(n,s){k(".slick-"+s,l)[t?"addClass":"removeClass"]("visually-hidden")}))}),a.slick(p(i)),a.parent().on("click.sl",".slick-down",function(n){n.preventDefault();n=k(this);k("html, body").stop().animate({scrollTop:k(n.data("target")).offset().top-(n.data("offset")||0)},800,"easeOutQuad"in k.easing&&i.easing?i.easing:"swing")}),i.mouseWheel&&a.on("mousewheel.sl",function(n,s){return n.preventDefault(),a.slick(s<0?"slickNext":"slickPrev")}),d||a.on("lazyLoaded lazyLoadError",function(n,s,i){var t;t=(i=k(t=i)).closest(".slide")||i.closest("."+C),i.parentsUntil(t).removeClass(function(n,s){return(s.match(/(\S+)loading/g)||[]).join(" ")})}),a.on("afterChange.sl",f),c&&(a.on("click.sl",x,h),a.on("click.sl",".media__icon--play",g)),r&&a.slick(C),k(e).addClass(b)}v.behaviors.slick={attach:function(n){n=t.context(n),t.once(s,y,e,n)},detach:function(n,s,i){"unload"===i&&t.once.removeSafely(y,e,n)}}}(jQuery,Drupal,drupalSettings,dBlazy);
;
