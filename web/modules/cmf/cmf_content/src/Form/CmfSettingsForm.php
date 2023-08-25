<?php

/**
 * @file
 * Contains Drupal\cmf_content\Form\CmfSettingsForm.
 */
 
namespace Drupal\cmf_content\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class CmfSettingsForm.
 *
 * @package Drupal\cmf_content\Form
 */
class CmfSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'cmf_content_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'cmf_content.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    $settings = $this->config('cmf_content.settings');

	/* Header Content */
        $form['header_content'] = [
            '#type' => 'details',
            '#title' => t('Header Content'),
            '#open' => TRUE,
        ];
        /* Header Content for Gov */
		$form['header_content']['header_site_name'] = array(
            '#title' => t('Enter Sitename for Website'),
            '#type' => 'textfield',
            '#description' => t('Please enter the Sitename for Header Section of your website.'),
            '#required' => false,
            '#default_value' => $settings->get('header_site_name'),
        );
        $form['header_content']['header_site_slogan'] = array(
            '#title' => t('Enter Sitename Slogon for Website'),
            '#type' => 'textfield',
            '#description' => t('Please enter the Sitename Slogan for Header Section of your website.'),
            '#required' => false,
            '#default_value' => $settings->get('header_site_slogan'),
        );
		
        $form['header_content']['header_goi_text'] = array(
            '#title' => t('Enter Header Title For Govt'),
            '#type' => 'textfield',
            '#description' => t('Please enter the GOI Title for Header Section of your website.'),
            '#required' => false,
            '#default_value' => $settings->get('header_goi_text'),
        );
        $form['header_content']['header_goi_text_url'] = array(
            '#title' => t('Enter Header URL For Govt'),
            '#type' => 'textfield',
            '#description' => t('Please enter the GOI URL for Header Section of your website.'),
            '#required' => false,
            '#default_value' => $settings->get('header_goi_text_url'),
        );

        /* Header Content for Ministry */
        $form['header_content']['header_sitename'] = array(
            '#title' => t('Enter Header Title for Minister'),
            '#type' => 'textfield',
            '#description' => t('Please enter the Sitename for Header Section of your website.'),
            '#required' => false,
            '#default_value' => $settings->get('header_sitename'),
        );
        $form['header_content']['header_sitename_url'] = array(
            '#title' => t('Enter Header URL for Minister'),
            '#type' => 'textfield',
            '#description' => t('Please enter the URL of Sitename for Header Section of your website.'),
            '#required' => false,
            '#default_value' => $settings->get('header_sitename_url'),
        );        
		
		/* Search URL */
        $form['search_url'] = [
            '#type' => 'details',
            '#title' => t('Search URL'),
            '#open' => False,
        ];
        $form['search_url']['search_site_name'] = array(
            '#title' => t('Enter Sitename for Search Target URL'),
            '#type' => 'textfield',
            '#description' => t('Please enter the Sitename to whom you want to search data. Ex. http://www.webinindia.com'),
            '#required' => false,
            '#default_value' => $settings->get('search_site_name'),
        );

		/* Social Links */
        $form['social_links'] = [
			'#type' => 'details',
			'#title' => t('Social links'),
			'#open' => FALSE,
			];
		$form['social_links']['facebook_url'] = array(
			'#title' => t('Enter your Facebook Page'),
			'#type' => 'textfield',
			'#description' => t('Enter your Facebook Page URL.'),
			'#required' => false,
			'#default_value' => $settings->get('facebook_url'),
		);
		$form['social_links']['twitter_url'] = array(
			'#title' => t('Enter your Twitter Page'),
			'#type' => 'textfield',
			'#description' => t('Enter your Twitter Page URL.'),
			'#required' => false,
			'#default_value' => $settings->get('twitter_url'),
		);
		$form['social_links']['youtube_url'] = array(
			'#title' => t('Enter your Youtube Page'),
			'#type' => 'textfield',
			'#description' => t('Enter your Youtube Page URL.'),
			'#required' => false,
			'#default_value' => $settings->get('youtube_url'),
		);
		
		/* Footer Content */
        $form['footer_content'] = [
            '#type' => 'details',
            '#title' => t('Footer Content'),
            '#open' => False,
        ];
        $form['footer_content']['footer_sitename'] = array(
            '#title' => t('Enter Sitename for Footer Section'),
            '#type' => 'textfield',
            '#description' => t('Please enter the Sitename of your website.'),
            '#required' => false,
            '#default_value' => $settings->get('footer_sitename'),
        );
		
		/* External Link Messages */
        $form['external_link_message'] = [
            '#type' => 'details',
            '#title' => t('External Link Messages'),
            '#open' => False,
        ];
        $form['external_link_message']['external_message'] = array(
            '#title' => t('Enter External Link Messages'),
            '#type' => 'textfield',
            '#description' => t('Please enter the External Link Messages which you want to display on external link.'),
            '#required' => false,
            '#default_value' => $settings->get('external_message'),
        );
		
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $settings = $this->configFactory->getEditable('cmf_content.settings');

    // Save configurations.	
	/* Header Content for Gov */
	$settings->set('header_site_name', $form_state->getValue('header_site_name'))->save();
    $settings->set('header_site_slogan', $form_state->getValue('header_site_slogan'))->save();
    $settings->set('header_goi_text', $form_state->getValue('header_goi_text'))->save();
    $settings->set('header_goi_text_url', $form_state->getValue('header_goi_text_url'))->save();
    $settings->set('header_sitename', $form_state->getValue('header_sitename'))->save();
    $settings->set('header_sitename_url', $form_state->getValue('header_sitename_url'))->save();
    $settings->set('search_site_name', $form_state->getValue('search_site_name'))->save();
	$settings->set('facebook_url', $form_state->getValue('facebook_url'))->save();
    $settings->set('twitter_url', $form_state->getValue('twitter_url'))->save();
    $settings->set('youtube_url', $form_state->getValue('youtube_url'))->save();
	$settings->set('footer_sitename', $form_state->getValue('footer_sitename'))->save();
	$settings->set('external_message', $form_state->getValue('external_message'))->save();
  }
}