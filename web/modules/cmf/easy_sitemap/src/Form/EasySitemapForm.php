<?php

/**
 * @file
 * Contains Drupal\easy_sitemap\Form\EasySitemapForm.
 */
 
namespace Drupal\easy_sitemap\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\system\Entity\Menu;

/**
 * Class CmfSettingsForm.
 *
 * @package Drupal\cmf_content\Form
 */
class EasySitemapForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'easysitemap';
  }

  protected function getEditableConfigNames() {
    return [
      'easy_sitemap.settings',
    ];
  }
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {


    $form = parent::buildForm($form, $form_state);
    $settings = $this->config('easy_sitemap.settings');
	
	/*
	* Get menu list.	
	*/
	
	$unlistedMenu = array("admin","tools","account");
	$all_menus = Menu::loadMultiple();
    $menus = array();
    foreach ($all_menus as $id => $menu) {
		if(!in_array($id,$unlistedMenu)){
			$menus[$id] = $menu->label();
		}
    }
	$listManu = $settings->get('menu_list');
	$defaultArray = array();
	if(empty($settings->get('menu_list'))){
		$defaultArray['footer']  = 'footer';
		$defaultArray['main'] = 'main';
		$listManu = $defaultArray;
	}	
    $form['menu_list'] = [
        '#type' => 'select',
        '#title' => $this
          ->t('Select Menu to display into sitemap'),		  
  	    '#multiple' => TRUE,		
        '#options' => $menus,		
		'#default_value' => $listManu,
      ]; 
    $form['#attributes'] = ['class' => ['easysitemap']];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
     $settings = $this->configFactory->getEditable('easy_sitemap.settings');

    // Save configurations. 
  /* Header Content for Gov */
    $settings->set('menu_list', $form_state->getValue('menu_list'))->save();
   
   
  }
}