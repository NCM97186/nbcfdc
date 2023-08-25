<?php
/**
 * @file
 * Custom Block
 */
namespace Drupal\cmf_design\Plugin\Block;
use Drupal\Core\Block\BlockBase;
/**
 * Provides a 'CMF Design Structure For Footer' block.
 *
 * @Block(
 *   id = "cmf_design_footer",
 *   admin_label = @Translation("CMF Design footer"),
 *   category = @Translation("Content"),
 * )
 */
class CmfDesignFooterBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
	public function build() {
		global $base_url;
		$settings = \Drupal::config('cmf_content.settings');
		$variables['footer_sitename'] = $settings->get('footer_sitename');	  
		/* Footer Content */
		$variables['stqc_certificate'] = $settings->get('stqc_certificate');	
		
		//$language = \Drupal::languageManager()->getCurrentLanguage()->getId();
		
	//	$lang = $language.getName();
		//$webmanaged = "Website Content Managed by";
		//$designedby = "Designed, Developed and Hosted by";
		//$nic = "National Informatics Centre";
		/* if( $variables['language'].getId() == 'en')
		{ */
		$str = '<div class="footer-bottom-wrapper">
				<div class="container common-container four_content footer-bottom-container">
					<div class="footer-content clearfix">
						<div class="logo-cmf"> 
							<a target="_blank" href="http://cmf.gov.in/" title="External link that opens in new tab">
							<img alt="cmf logo" src="'.$base_url.'/'.drupal_get_path('module', 'cmf_design').'/images/cmf-logo.png"></a>
						</div>
						<div class="copyright-content"><span> Website Content Managed by  <strong>'.$variables['footer_sitename'].'</strong> 
							Ministry of Home Affairs, GoI Best viewed in Mozilla, Chrome and equivalent browsers, Copyright 2021 All Rights Reserved <br> 
							Designed, Developed and Hosted by <a target="_blank" title="NIC, External Link that opens in a new window" href="http://www.nic.in/">
								<strong>National Informatics Centre</strong></a><strong> ( NIC )</strong>
							</span> 
						</div>
					</div>
				</div>
			</div>';
		/* } else {
		$str = '<div class="footer-bottom-wrapper">
				<div class="container common-container four_content footer-bottom-container">
					<div class="footer-content clearfix">
						<div class="logo-cmf"> 
							<a target="_blank" href="http://cmf.gov.in/" title="बाहरी लिंक नई विंडो में खुलती है">
							<img alt="cmf logo" src="'.$base_url.'/'.drupal_get_path('module', 'cmf_design').'/images/cmf-logo.png"></a>
						</div>
						<div class="copyright-content"><span> Website Content Managed by  <strong>'.$variables['footer_sitename'].'</strong> 
							विषयवस्तु प्रबंधन, GoI Best viewed in Mozilla, Chrome and equivalent browsers, Copyright 2021 All Rights Reserved <br> 
							अभिकल्पित, विकसित और परिचारित <a target="_blank" title="एनआईसी, बाहरी लिंक नई विंडो में खुलती है" href="http://www.nic.in/">
								<strong> परिचारित राष्ट्रीय सूचना विज्ञान केन्द्र</strong></a><strong> ( एनआईसी )</strong>
							</span> 
						</div>
					</div>
				</div>
			</div>';
		}	 */
		return array(
		  '#type' => 'markup',
		  '#markup' => $str,
		);
	}
}


/*
विषयवस्तु प्रबंधन Ministry of Home Affairs, GoI Best viewed in Mozilla, Chrome and equivalent browsers ,Copyright© 2021 All Rights Reserved
अभिकल्पित, विकसित और परिचारित राष्ट्रीय सूचना विज्ञान केन्द्र
*/