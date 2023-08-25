<?php

/**
 * @file
 * Contains Drupal\cmf_content\Form\CmfSettingsForm.
 */
 
namespace Drupal\simple_metakey\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class CmfSettingsForm.
 *
 * @package Drupal\cmf_content\Form
 */
class SimpleMetakeyForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'simple_metakey';
  }

  protected function getEditableConfigNames() {
    return [
      'simple_metakey.settings',
    ];
  }
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
     $form = parent::buildForm($form, $form_state);
    $settings = $this->config('simple_metakey.settings');
    $form['metakey'] = [
      '#title' => t('Enter Meta Keywords'),
      '#type' => 'textarea',      
      '#default_value' => $settings->get('metakey'),
      '#description' => t('Please metakey words. Add "," to add more metakey word.'),
      '#attributes' => [
        'placeholder' => $this->t('Meta Keywords'),
        'class' => ['cmfgs-input'],
      ],
      '#size' => NULL,
    ];
    $form['metakey_desc'] = [
      '#title' => t('Enter Meta Description'),
      '#type' => 'textarea',      
      '#default_value' => $settings->get('metakey_desc'),
      '#attributes' => [
        'placeholder' => $this->t('Meta description'),
        'class' => ['cmfgs-input'],
      ],
      '#description' => t('Please meta description.'),
      '#size' => NULL,
    ];

    $form['#attributes'] = ['class' => ['cmfgs']];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
     $settings = $this->configFactory->getEditable('simple_metakey.settings');

    // Save configurations. 
  /* Header Content for Gov */
    $settings->set('metakey', $form_state->getValue('metakey'))->save();
    $settings->set('metakey_desc', $form_state->getValue('metakey_desc'))->save();

   
   
  }
}