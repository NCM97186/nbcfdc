<?php

namespace Drupal\cmf_google_search\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Defines a form for performing a simple redirect to display search results.
 */
class SearchForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'cmf_google_search_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['s'] = [
      '#type' => 'textfield',
      '#title' => t('Search'),
      '#default_value' => $form_state->getValue('s', $this->getRequest()->query->get('s', '')),
      '#attributes' => [
        'placeholder' => $this->t('Search site...'),
        'class' => ['cmfgs-input'],
      ],
      '#theme_wrappers' => [],
      '#size' => NULL,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Search'),
      '#attributes' => ['class' => ['cmfgs-submit']],
    ];

    $form['#attributes'] = ['class' => ['cmfgs']];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $form_state->setRedirectUrl(Url::fromRoute('cmf_google_search.search_page', [], [
      'query' => [
        's' => $form_state->getValue('s'),
      ],
    ]));
  }

}
