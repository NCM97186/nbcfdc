// JavaScript Document For The mega Menu
(function ($, window, document){
    "use strict";
    $(".main-menu").accessibleMegaMenu({
        /* prefix for generated unique id attributes, which are required to indicate aria-owns, aria-controls and aria-labelledby */
        uuidPrefix: "accessible-megamenu",
        /* css class used to define the megamenu styling */
        menuClass: "nav-menu",
        /* css class for a top-level navigation item in the megamenu */
        topNavItemClass: "nav-item",
        /* css class for a megamenu panel */
        panelClass: "sub-nav",
        /* css class for a group of items within a megamenu panel */
        panelGroupClass: "sub-nav-group",
        /* css class for the hover state */
        hoverClass: "hover",
        /* css class for the focus state */
        focusClass: "focus",
        /* css class for the open state */
        openClass: "open",
		toggleButtonClass: "accessible-megamenu-toggle", // default css class responsive toggle button
		openDelay: 0, // default open delay when opening menu via mouseover
		closeDelay: 250, // default open delay when opening menu via mouseover
		openOnMouseover: true // default setting for whether menu should open on mouseover
    });
	
$(document).ready(function(e){
		 if($(document).innerWidth() > 768 && $(document).innerWidth() <900){
				$(".quicktabs-tabs").removeClass('resp-tabs-list hor_1');
				$(".quicktabs-tabs li").removeClass('resp-tab-item hor_1');
				$("#quicktabs-container-home_quicktab").removeClass('resp-tabs-container hor_1');
				//$(".quicktabs-loaded").attr("href","javascript:void(0)");
		}else if ($(document).innerWidth() < 700){
				$(".quicktabs-tabs").addClass('resp-tabs-list hor_1');
				$(".quicktabs-tabs li").addClass('resp-tab-item hor_1');
				$("#quicktabs-container-home_quicktab").addClass('resp-tabs-container hor_1');
				$(".quicktabs-loaded").attr("href","javascript:void(0)");
				
		}else if($(document).innerWidth() > 940){
				$(".quicktabs-tabs").removeClass('resp-tabs-list hor_1');
				$(".quicktabs-tabs li").removeClass('resp-tab-item hor_1');
				$("#quicktabs-container-home_quicktab").removeClass('resp-tabs-container hor_1');
				//$(".quicktabs-loaded").attr("href","javascript:void(0)");
		}
 });
$(document).ready(function(e){
	if ($(document).innerWidth() > 940){
		$('.main-menu li').each(function (index, element){
			//console.log("Element=>" + element.innerText);
			if ($(this).children('div').length > 0){
				$(this).find('> a').append('<span class="indicator">+</span>');
				$(this).find('> span').append('<span class="indicator">+</span>');
				$(this).find('> span').attr('tabindex', '0');
				var cnt= parseInt($(this).children('div').find('li').length);
				var cnt1= parseInt($(this).children('div').find('span').length);
				//console.log("Total Child No: " + cnt);
				//console.log("Total Sub Child No: " + cnt1);
				//var cnt= cnt + cnt1;
				//console.log("Now Total Child No: " + cnt);
				//Below logic breaks menu into multiple columns of 7 items each. Custom code written by Arun on 5 Jul 2022
				if(cnt< 7 ){
					//console.log("In 1st Loop => Total Count: " + cnt);
					$(this).find('div').addClass('col-1 right-pull');
					//$(this).find('li').append('</ul><ul class="Arun1">');
					if(cnt1 > 0 ){
						//console.log("In 1st Loop => Total Count: " + cnt);
						$(this).find('div').addClass('col-1 right-pull');
						//$(this).find('li').append('</ul><ul class="Arun1">');
					}
				}
				
				else if(cnt> 6 && cnt< 14){
					//console.log("In 2nd Loop => Total Count: " + cnt);
					$(this).find('div').addClass('col-2 right-pull'); 
					//$(this).find('li').append('</ul><ul class="Arun2">');
					var menuHTML = "<ul class='sub-nav-group'>"; //$(this).children('div').find('ul').outerHTML;
					$(this).children('div').find('li').each(function (index, element){
						menuHTML += element.outerHTML;
						if(index==6)
							menuHTML += '</ul><ul class="Arun2">';
						
					});
					menuHTML +="</ul>";
					$(this).children('div').html(menuHTML);
					//console.log(menuHTML);
				}else if(cnt>13){
					//console.log("In 3rd Loop => Total Count: " + cnt);
					$(this).find('div').addClass('col-3 right-pull');
					//$(this).find('li').append('</ul><ul class="Arun3">');
					var menuHTML = "<ul class='sub-nav-group'>"; //$(this).children('div').find('ul').outerHTML;
					$(this).children('div').find('li').each(function (index, element){
						menuHTML += element.outerHTML;
						if(index==6 || index==14 || index==22 || index==30 || index==38) //Code added to break after every 8 items. Not dynamic but works for our need.
							menuHTML += '</ul><ul class="Arun2">';		
					});
					menuHTML +="</ul>";
					$(this).children('div').html(menuHTML);
					//console.log(menuHTML);
				}
			}
		});
	}
	$('#main_menu .nav-item>a').mouseleave(function(e){
		if($(this).parent().children('div').length > 0){
		
		}else{
			
			$(this).blur();
		}
	});
	// de focus when mouse leave, it was geting stucked
	$('#main_menu .nav-item').mouseleave(function(e) {
		$(this).children('a').blur();
	})

	// on focus open dropdown for norml element
	$('#main_menu .nav-item a').focus(function(e) {	
		if($(this).parent().children('div').length > 0){
			if($(document).innerWidth() > 940){
				$(this).click();
			}
		}	
	});
	
	// on focus open dropdown for span
	$('#main_menu .nav-item>span').focus(function(e){	
		if($(this).parent().children('div').length > 0){
			if($(document).innerWidth() > 940){
				$(this).click();
			}
		}	
	});
	
	// On focus open dropdown for anchor
	$(document).on('focus', '#overflow_menu .nav-item a', function () {
	if($(this).parent().children('div').length >0){
				if($(document).innerWidth() > 940){
					$(this).click();
				}
	}
	//alert(1);
	});
	
   // On focus open dropdown for span
	$(document).on('focus', '#overflow_menu .nav-item>span', function (){
		if($(this).parent().children('div').length > 0){
					if($(document).innerWidth() > 940){
						$(this).click();
					}
		}
	});
});

//Added by shubh Tiwari 25 Sept 2019
	function menu_toggle(){		
		$('.showhide').click(function(e) {
			$('#main_menu').stop().slideToggle('slow');
			$('.showhide').toggleClass('close');
		});
		if($(document).innerWidth() < 940){
			$('.main-menu li').each(function (index, element) {
                if ($(this).children('div').length > 0) {
                    $(this).find('> span').append('<span class="indicator">+</span>');
					$(this).find('> a').append('<span class="indicator">+</span>');
                }
            });
			
			$('.main-menu .nav-item').on('click', 'span', function (e) {
				$(this).siblings('div').slideToggle('slow');
			});			
		}
	}
	$(window).resize(function(e){
		if($(document).innerWidth() > 940){
			$('.main-menu .nav-item').each(function(index, element) {
				$(this).children('a').removeClass('sub-nav');
			})
				$('#main_menu').show();
				$('#main_menu').attr('style','');
			
			$('.megamenu-wraper .container .showhide').css('display', 'none');
			
		}else{
			$('.megamenu-wraper .container .showhide').css('display', 'block');
		}
		//alert(1);	
	})
//window.onload =  menu_toggle(); 
    $(document).ready(function (e) {
		menu_toggle();
        if ($(document).innerWidth() > 940) {
            var t = $('#main_menu').width();
            var m = 0;
            $('#main_menu .nav-menu .nav-item').each(function (index, element){
                m = m + $(this).width();
                if (m > (t - 100)) {
                    $('#btn-more-toggle').parent().remove(); 
                    $(this).parent().append('<li class="btn-more-li"><a class="btn-more" title="'+ Drupal.t('More') + '" id="btn-more-toggle" href="#">' + Drupal.t('More') + '</a></li>');
                    $(this).remove();
                    $('#overflow_menu ul.nav-menu').append('<li class="nav-item">' + $(this).html() + '</li>');
                }
            });
        }else {
            $('.main-menu .sub-nav').slideUp();
            $('.main-menu').slideUp();
        }
		$('#overflow_menu').hide();
		var oh = $('#btn-more-toggle').height();
		$('#btn-more-toggle').mousedown(function(e) {
			var mousedown = true;
			//debugger;
			//console.log($('#overflow_menu ul').height() + $('#main_menu').height());
			e.preventDefault();
			$('#overflow_menu').stop().slideToggle(500);
			var mHight = $('#main_menu ul li').first().height();
			$(this).toggleClass('opened');
			if($(this).hasClass('opened')){
				var t = $('#overflow_menu ul').height() + $('#main_menu').height()+3;
				$(this).stop().animate({height: t+'px'},500);
			}else{
				$(this).stop().animate({height: mHight+'px'},500);
			}
		});
		$('#btn-more-toggle').focus(function(e) {
			//debugger;
			//console.log($('#overflow_menu ul').height() + $('#main_menu').height());
			e.preventDefault();
			$('#overflow_menu').stop().slideToggle(500);
			var mHight = $('#main_menu ul li').first().height();
			$(this).toggleClass('opened');
			if($(this).hasClass('opened')){
				var t = $('#overflow_menu ul').height() + $('#main_menu').height()+3;
				$(this).stop().animate({height: t+'px'},500);
			}else{
				$(this).stop().animate({height: mHight+'px'},500);
			}
		});
    });
    // third level menu
    $(document).ready(function (e) {
        if ($(document).innerWidth() > 940) {
            $('.sub-nav-group li').mouseover(function () {
                $('.sub-nav').css('height', 'auto');
                $('.sub-sub-nav').css('height', 'auto');
                if ($(this).children().length > 1) {
                    var aa = $('.sub-nav-group').height();
                    $(this).find(".sub-sub-nav").addClass('open');
                    $(this).find("a").addClass('hover');
                    var parentHieght = $(this).parents('.sub-nav-group').height();
                    var subMenuHeight = $(this).find('.sub-sub-nav-group').height();
                    var diffOfH = subMenuHeight - parentHieght;
                    var positionofbutton = $(this).parents('.sub-nav-group').parents('li').position();
                    var docWidth = $(document).width();
                    var mainMenuContainerwidth = $('.container').width();
                    var childDivWidth = $(this).parents('.sub-nav').width();
                    var subChildDivWidth = $(this).children('.sub-sub-nav').width();
                    var finalDiff = (docWidth) - (((docWidth - mainMenuContainerwidth) / 2) + positionofbutton.left);
                    var totalWidthofbothChild = childDivWidth + subChildDivWidth;
                    if (finalDiff < totalWidthofbothChild) {
                        $(this).find('.sub-sub-nav').css({'left': 'auto', 'right': '100%', 'top': '0'});
                    } else {
                        $(this).find('.sub-sub-nav').css({'left': '100%', 'right': 'auto', 'top': '0'});
                    }
                    if (diffOfH <= 0) {
                        $(this).find('.sub-sub-nav').height(parentHieght);
                    } else {
                        $(this).parents('.sub-nav').height(subMenuHeight);
                    }
                } else {
                    $('.sub-nav').css('height', 'auto');
                    $(this).find('.sub-sub-nav').height(parentHieght);
                    var positionofbutton = $(this).parents('.sub-nav-group').parents('li').position();
                }
            });
            $('.sub-nav-group li').mouseleave(function () {
                $('.sub-nav').css('height', 'auto');
                $('.sub-sub-nav').css('height', 'auto');
                if ($(this).children().length > 0) {
                    $(".sub-sub-nav").removeClass('open');
                    $(".sub-nav-group li a").removeClass('hover');
                }
            });
            $('ul.nav-menu > li').mouseover(function () {
                var positionofbutton = $(this).position();
                var childDivWidth = $(this).find('.sub-nav').width();
                var totalDiff = (($(document).width() * 95) - (positionofbutton.left * 100)) / 100;
                if (totalDiff < childDivWidth) {
                    $(this).find('.sub-nav').css({'left': 'auto', 'right': '0'});
                } else {
                    $(this).find('.sub-nav').css({'left': '0', 'right': 'auto'});
                }
            });
            $('.sub-nav-group li').focusin(function () {
                $('.sub-nav').css('height', 'auto');
                $('.sub-sub-nav').css('height', 'auto');
                if ($(this).children().length > 1) {
                    var aa = $('.sub-nav-group').height();
                    $(this).find(".sub-sub-nav").addClass('open');
                    $(this).find("a").addClass('hover');
                    var parentHieght = $(this).parents('.sub-nav-group').height();
                    var subMenuHeight = $(this).find('.sub-sub-nav-group').height();
                    var diffOfH = subMenuHeight - parentHieght;
                    if (diffOfH <= 0) {
                        $(this).find('.sub-sub-nav').height(parentHieght);
                    } else {
                        $(this).parents('.sub-nav').height(subMenuHeight);
                    }
                } else {
                    $('.sub-nav').css('height', 'auto');
                    $(this).find('.sub-sub-nav').height(parentHieght);
                }
            });
            $('.sub-nav-group li li:last-child').focusout(function () {
                $('.sub-nav').css('height', 'auto');
                $('.sub-sub-nav').css('height', 'auto');
                if ($(this).children().length > 0) {
                    $(".sub-sub-nav").removeClass('open');
                    $(".sub-nav-group li a").removeClass('hover');
                }
            });
        }
    });
}(jQuery, window, document));