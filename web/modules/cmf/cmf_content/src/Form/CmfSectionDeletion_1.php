<?php

/**
 * @file
 * Contains Drupal\cmf_content\Form\CmfSectionDeletion.
 */
 
namespace Drupal\cmf_content\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class CmfSectionDeletion.
 *
 * @package Drupal\cmf_content\Form
 */
class CmfSectionDeletion extends ConfigFormBase {

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
      'cmf_content.deletesection',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    $deletesection = $this->config('cmf_content.deletesection');
			
	/* Section Deletion */
	$form['section_deletion'] = [
		'#type' => 'details',
		'#title' => t('Clear Section and Subsection Data'),
		'#open' => true,
	];
	$form['section_deletion']['section_deletion_name'] = array(
		'#title' => t('Click Below Link to Clear Section and Subsection Deleted Data'),
		'#type' => 'details',
		'#open' => true,
		'#description' => t('<a href="/web/delete_section_data.php" >Click Me</a> to Clear Section and Subsection Data.'),
		'#required' => false,
		'#default_value' => $deletesection->get('section_deletion_name'),
	);		
	
	/* Cache Clear */
	$form['cache_clear'] = [
		'#type' => 'details',
		'#title' => t('Clear Cache Data'),
		'#open' => true,
	];
	$form['cache_clear']['cache_clear_data'] = array(
		'#title' => t('Click Below Link to Clear Cache Data'),
		'#type' => 'details',
		'#open' => true,
		'#description' => t('<a href="/web/clear_cache.php" >Click Me</a> to Clear Cache Data.'),
		'#required' => false,
		'#default_value' => $deletesection->get('cache_clear_data'),
	);	
		
    return $form;
  }

  /**
   * {@inheritdoc}
   */
}