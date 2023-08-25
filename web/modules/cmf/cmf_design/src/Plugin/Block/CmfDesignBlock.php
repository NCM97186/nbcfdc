<?php
/**
 * @file
 * Custom Block
 */
namespace Drupal\cmf_design\Plugin\Block;
use Drupal\Core\Block\BlockBase;
/**
 * Provides a 'CMF Design Structure' block.
 *
 * @Block(
 *   id = "cmf_design",
 *   admin_label = @Translation("CMF Design Structure"),
 *   category = @Translation("Content"),
 * )
 */
class CmfDesignBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
  global $base_url;
  $language = \Drupal::languageManager()->getCurrentLanguage()->getId(); 
  //$module_url = $base_url.'/'.drupal_get_path('module', 'cmf_design');
  //$module_url = drupal_get_path('module', 'cmf_design');
//echo "<br>Base URL===>".$base_url;
//echo "<br>Module URL===>".$module_url;
  $module_url="/web/modules/cmf/cmf_design/";
  //$module_url="http://10.247.137.192/web/modules/cmf/cmf_design/";
  $settings = \Drupal::config('cmf_content.settings');
	/* Header Content */
	$variables['header_site_name'] = $settings->get('header_site_name');
	$variables['header_site_slogan'] = $settings->get('header_site_slogan');
	$variables['header_goi_text'] = $settings->get('header_goi_text');
	$variables['header_goi_text_url'] = $settings->get('header_goi_text_url');
	$variables['header_sitename'] = $settings->get('header_sitename');
	$variables['header_sitename_url'] = $settings->get('header_sitename_url');	
	/* Search Site URL Content */
	$variables['search_site_name'] = $settings->get('search_site_name');
	/* Social Links */  
	$variables['facebook_url'] = $settings->get('facebook_url');
	$variables['twitter_url'] = $settings->get('twitter_url');
	$variables['youtube_url'] = $settings->get('youtube_url');
	/* Footer Content */
	$variables['footer_sitename'] = $settings->get('footer_sitename');
	/* External URL Message */
	$variables['external_message'] = $settings->get('external_message');
	// logo needs to be placed within specified folder
	//$site_config = \Drupal::config('system.site');
	$sitemap = t("Sitemap");
	$social_medias= t("Social Medias");
	$variables['logopath'] = file_url_transform_relative(file_create_url(theme_get_setting('logo.url')));
	$increase= 'increase';
	if($language == 'hi'){
		$SkipContent = "मुख्य विषयवस्तु में जाएं";
		$LanguageSelection = '<a href="#" title="Select Language">भाषा</a> 
				<ul>				
				<li><a target="_blank" href="'.$base_url.'/en" lang="" class="alink" title="Click here for English version.">English</a></li>
				</ul>
				';
	} else {
		$SkipContent = "Skip to main content";
		$LanguageSelection = '<a href="#" title="Select Language">Language</a> 
				<ul>
				<li><a target="_blank" href="'.$base_url.'/hi" lang="hi" class="alink" title="Click here for हिन्दी version.">हिन्दी</a></li>
				</ul>
				';
	}
							  
							  
  	$str = '<div class="region region-header-top">
            <div id="block-cmf-content-header-region-block" class="block block-cmf-content first last odd">
               
               <div class="wrapper common-wrapper">
                  <div class="container common-container four_content top-header">
                     <div class="common-left clearfix">
                        <ul>
                           <li class="gov-india"><span class="responsive_go_hindi" lang="hi"><a target="_blank" href="https://mha.gov.in/hi" title="भारत सरकार ( बाहरी वेबसाइट जो एक नई विंडो में खुलती है)">भारत सरकार</a></span>                           </li>
                           <li class="ministry"><span class="li_eng responsive_go_eng"><a target="_blank" href="https://mha.gov.in/" title="'.$variables['header_goi_text'].',External Link that opens in a new window" >Government of india</a></span></li>
                        </ul>
                     </div>
					 
                     <div class="common-right clearfix">
                        <ul id="header-nav">
                           <li class="ico-skip cf"><a href="#skipCont" title="skip to mian Content">'.$SkipContent.'</a>
                           </li>
                           <li class="ico-site-search cf">
                              <a href="#" id="toggleSearch" title="Site Search">
                              <img src="/web/modules/cmf/cmf_design/images/ico-site-search.png" alt="Site Search" class="top"/></a>
                              <div class="search-drop both-search" style="">
                                 
                              </div>
                           </li>
                           <li class="ico-accessibility cf">
                              <a href="#" id="toggleAccessibility" title="Accessibility Dropdown">
                              <img class="top" src="/web/modules/cmf/cmf_design/images/ico-accessibility.png" alt="Accessibility Dropdown" />
                              </a>
                              <ul style="visibility: hidden;">
                                 <li><a onClick="set_font_size(increase)" title="Increase font size" class="font-size" id="increase" href="#">A<sup>+</sup>
                                    </a> 
                                 </li>
                                 <li> <a onClick="set_font_size()" title="Reset font size" class="font-size"  id="remain" href="#">A<sup>&nbsp;</sup></a> </li>
                                 <li> <a onClick="set_font_size(decrease)" class="font-size" id="decrease" title="Decrease font size" href="#">A<sup>-</sup></a> </li>
                                 <li> <a href="#" class="high-contrast dark" title="High Contrast">A</a> </li>
                                 <li> <a href="#" class="high-contrast light" title="Normal Contrast" style="display: none;">A</a> </li>
                              </ul>
                           </li>
                           <li class="ico-social cf">
                              <a href="#" id="toggleSocial" title="'.$social_medias.'">
                              <img class="top" src="/web/modules/cmf/cmf_design/images/ico-social-new.png" alt="'.$social_medias.'" /></a>
                              <ul>
                                 <li><a target="_blank" title="External Link that opens in a new window" href="'.$variables['facebook_url'].'"><img alt="Facebook Page" src="'.$module_url.'/images/ico-facebook.png"></a></li>
                                 <li><a target="_blank" title="External Link that opens in a new window" href="'.$variables['twitter_url'].'"><img alt="Twitter Page" src="'.$module_url.'/images/ico-twitter.png"></a></li>
                                 <li><a target="_blank" title="External Link that opens in a new window" href="'.$variables['youtube_url'].'"><img alt="youtube Page" src="'.$module_url.'/images/ico-youtube.png"></a></li>
                              </ul>
                           </li>
						   
                           <li class="ico-sitemap cf"><a href="'.$base_url.'/sitemap" title="'.$sitemap.'">
                              <img class="top" src="/web/modules/cmf/cmf_design/images/ico-sitemap.png" alt="'.$sitemap.'" /></a>
                           </li>                           
                           <li class="hindi cmf_lan">'.$LanguageSelection.'</li>
                        </ul>
                     </div>
                  </div>
               </div>
            </div>
            <p id="scroll" style="display: none;"><span></span></p>
         </div>';
		 // $str = 'test';
    return array(
      '#type' => 'markup',
      '#markup' => $str,
	  '#attached' => [
        'drupalSettings' => [
          'search_site_name' => $variables['search_site_name'],
		  'external_message' => $variables['external_message'],
        ],
      ],
    );
  }
}