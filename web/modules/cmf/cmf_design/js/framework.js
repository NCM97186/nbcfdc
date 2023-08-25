//fetch search site value from config
let searchSiteURL = null;
let externalURLMessage = null;
(function ($) {
  //let searchSiteURL = null;
  Drupal.behaviors.cmfDesign = {
    attach: function(context, settings) {
      searchSiteURL = settings.search_site_name;
      externalURLMessage = settings.external_message;
	  //alert("hi => "+ externalURLMessage);
	  searchSiteURL = "https://mha.gov.in";
	  //console.log("====>"+searchSiteURL);
    }
  }
})(jQuery);



jQuery(document).ready(function ($) {
  // External Link.
  //alert("hi"+ externalURLMessage);
  $('.ext').click(function () {
	//if (confirm("This link will take you to an external web site.") == true) {
	if (confirm( externalURLMessage ) == true) {
	  return true;
	} else {
	  return false;
	}
  });
});



//console.log("xxxxxx>"+searchSiteURL);


var lastPart = function($url){
	var url = $url;
	// Split URL into parts and store them as array 
	var parts = url.split("/");
	// Get value of last part
	var last_part = parts[parts.length - 1];
	// In case there is a '/' at the end
	if(last_part === '') last_part = parts[parts.length - 2];
		return last_part;
}
	
jQuery(document).ready(function(){
	jQuery("#edit-search-block-form--2").attr("placeholder", "Search - Keyword, Phrase");
	jQuery(".gtranslate select").attr("id","gtranslate");			   
	jQuery("#gtranslate").before('<label class="notdisplay" for="gtranslate">Google Translate</label>');
	//contrast
	if(getCookie('contrast') == 0 || getCookie('contrast') == null){
	jQuery(".light").hide();
	jQuery(".dark").show();
	}else{
	jQuery(".light").show();
	jQuery(".dark").hide();	
	}
	jQuery(".search-drop").css("display", "none");
	jQuery(".common-right ul li ul").css("visibility", "hidden");

	// Fix Header

	var num = 36; //number of pixels before modifying styles
	jQuery(window).bind('scroll', function () {
		if (jQuery(window).scrollTop() > num) {
		jQuery('.fixed-wrapper').addClass('sticky');
		} else {
		jQuery('.fixed-wrapper').removeClass('sticky');
		}
	});		
		
	
	// Mobile Nav	
	jQuery('.sub-menu').append('<i class="fa fa-caret-right"></i>');
	jQuery('.toggle-nav-bar').click(function(){	
	jQuery('#nav').slideToggle();
	//jQuery('#nav li').removeClass('open');
	
	/*jQuery("#nav li").click(function(){
		jQuery("#nav li").removeClass('open');
		jQuery(this).addClass('open');
	}); */
	
		jQuery("#nav li").hover(
		function() {
		jQuery( this  ).addClass( "open" );
		}, function() {
		jQuery( this ).removeClass( "open" );
		}
		);		
	});


	//Skip Content
	jQuery('a[href^="#skipCont"]').click(function() {
		jQuery('html,body').animate({ scrollTop: jQuery(this.hash).offset().top}, 500);
		//return false;
		//e.preventDefault();
	});

	//var BASE_URL = window.location.href.split('#')[0];
	//var action = BASE_URL+"search/site-search";
	//var action = "http://www.google.com/search";
	var action = searchSiteURL;
	var action = searchSiteURL;
	//console.log("--->"+action);
	if(searchSiteURL.includes("://"))
		var searchSiteName = searchSiteURL.split('://')[1];
	else if(searchSiteURL.includes("www."))
		var searchSiteName = searchSiteURL.split('www.')[1];
	else
		var searchSiteName = searchSiteURL;

	var html='';
	html += '<div class="google-find">';
	html +='<form method="get" action="'+ action +'">';
	html +='<label for="search_key_g" class="notdisplay">Search</label>';
	html +='<input type="text" name="keys" value="" id="search_key_g"/> ';
	html +='<input type="button" value="Search" class="bttn-search" id="btnSearch" class="submit" />';	
	html +='</form>';
	html +='</div>';


// Serach top remove when we go to the seach page 
	var pathname = '';
	//pathname = lastPart(window.location.pathname); // uncomment on production
	//console.log("===>"+pathname);
	pathname = "/web/";
	//console.log("===>"+pathname);
	if(jQuery.trim(pathname) == "site-search"){
		jQuery("#header-nav li").eq(1).remove();
		jQuery('.cmf-site-breadcrumb li:nth-child(2)').remove();
		//jQuery(".views-field-body").addClass('msde-contact');
		//jQuery(".msde-contact").css('display', 'none');
		//jQuery('.organization-chart').append(contact);
		
	}else{
		jQuery('.search-drop').append(html);
		//jQuery(".views-field-body").removeClass('msde-contact');
	}

// Toggle Search

    jQuery("#toggleSearch").click(function(e) { 
        jQuery(".search-drop").toggle();
        e.stopPropagation();
    });

    jQuery(document).click(function(e) {
        if (!jQuery(e.target).is('.search-drop, .search-drop *')) {
            jQuery(".search-drop").hide();
        }
    });
	
	function searchData(me){

	}

	jQuery("#btnSearch").click(function(e) {
		var searchData = document.getElementById("search_key_g").value;
		
		var BASE_URL = "https://mha.gov.in";
		

		var searchData = document.getElementById("search_key_g").value;
		var action = BASE_URL+"/search/site-search?keys="+searchData;
		window.open(action, "_blank");
		
		/* //alert("search Site URL: " + searchSiteURL);
		//alert("search Site Name: " + searchSiteName);

		var searchData = document.getElementById("search_key_g").value;
		//alert("search text value: " + searchData);

		var searchOption = "";
		var ele = document.getElementsByName('sitesearch');  
				//alert("==="+ ele.length);
		for(i = 0; i < ele.length; i++) { 
			//alert("==="+ele[i].value);
			if(ele[i].checked) 
				var searchOption = ele[i].value; 
		} 
		//alert("Search Option: " + searchOption);
		//var BASE_URL = "https://mha.gov.in/web";
		var BASE_URL = "https://mha.gov.in/web";

		if(searchOption == "The Web"){
			//var BASE_URL = window.location.href.split('#')[0];			
			var action = BASE_URL+"/goi_search/search?search_key="+searchData;
			window.open(action, "_blank");
		}
		if(searchOption != "The Web"){
			//var action = searchSiteURL+"/search/site-search?keys="+searchData;
			//var BASE_URL = window.location.href.split('#')[0];
			var action = BASE_URL+"/search/site-search?keys="+searchData;
			window.open(action, "_blank");
		} */
	});

});


jQuery(document).ready(function(){	
	jQuery("#block-mainnavigation-2-menu div > ul" ).attr("id","nav");
	dropdown1('nav','hover',10);
	dropdown1("header-nav", "hover", 20);
});


jQuery(document).ready(function(){	
	jQuery('.lang_select').change(function() {
          var url = jQuery(this).val(); // get selected value
          if (url) { // require a URL
              window.location = url; // redirect
          }
          return false;
      });
});


//Drop down menu for Keyboard accessing

function dropdown1(dropdownId, hoverClass, mouseOffDelay) { 
	if(dropdown = document.getElementById(dropdownId)) {
		var listItems = dropdown.getElementsByTagName('li');
		for(var i = 0; i < listItems.length; i++) {
			listItems[i].onmouseover = function() { this.className = addClass(this); }
			listItems[i].onmouseout = function() {
				var that = this;
				setTimeout(function() { that.className = removeClass(that); }, mouseOffDelay);
				this.className = that.className;
			}
			var anchor = listItems[i].getElementsByTagName('a');
			anchor = anchor[0];
			anchor.onfocus = function() { tabOn(this.parentNode); }
			anchor.onblur = function() { tabOff(this.parentNode); }
		}
	}
	
	function tabOn(li) {
		if(li.nodeName == 'LI') {
			li.className = addClass(li);
			tabOn(li.parentNode.parentNode);
		}
	}
	
	function tabOff(li) {
		if(li.nodeName == 'LI') {
			li.className = removeClass(li);
			tabOff(li.parentNode.parentNode);
		}
	}
	
	function addClass(li) { return li.className + ' ' + hoverClass; }
	function removeClass(li) { return li.className.replace(hoverClass, ""); }
}

//<![CDATA[
jQuery(function ()
{
jQuery('table').wrap('<div class="scroll-table1"></div>');
jQuery(".scroll-table1").before( "<div class='guide-text'>Swipe to view <i class='fa fa-long-arrow-right'></i></div>" );

});
//]]>


jQuery(document).ready(function(){
	var params = new Array();
	var count = 0;
	jQuery('table.views-table thead tr th').each(function () {
		params[count] = jQuery(this).text();
		count++;	
	});
	jQuery('table.views-table tbody tr').each(function () {
		for(var j = 1; j <= count; j++){
			jQuery('td:nth-child('+j+')', this).attr("data-label",params[j-1]);
		}
	});
});


function burstCache() {
var url = window.location.href;
if(base_url != url && base_url+"/" != url){
if (!navigator.onLine) {
document.body.innerHTML = "Loading...";
window.location = "/";
}
}
}
//window.onload = burstCache;





jQuery("#btnSearchNew").click(function(e) 
{
	var searchData = document.getElementById("search_key_new").value;
	//alert("search text value: " + searchData);
	
	var BASE_URL = window.location.href.split('?')[0];
	//alert(BASE_URL);
	var action = BASE_URL+"?search_key="+searchData;
	window.open(action, "_self");
});
