<?php

namespace Drupal\filemime\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StreamWrapper\StreamWrapperInterface;
use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Symfony\Component\Mime\MimeTypeGuesserInterface;

/**
 * Implements the file MIME apply settings form.
 */
class FileMimeApplyForm extends ConfirmFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'filemime_apply_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Apply MIME type mapping to all uploaded files?');
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return $this->t('Are you sure you want to apply the configured MIME type mapping to all previously uploaded files? The MIME type for @count uploaded files will be regenerated.', ['@count' => self::count()]);
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Apply');
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('filemime.settings');
  }

  /**
   * {@inheritdoc}
   *
   * @param mixed[] $form
   *   Apply form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Form state.
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    batch_set([
      'operations' => [[[static::class, 'process'], []]],
      'finished' => [static::class, 'finished'],
      'title' => $this->t('Processing File MIME batch'),
      'init_message' => $this->t('File MIME batch is starting.'),
      'progress_message' => $this->t('Please wait...'),
      'error_message' => $this->t('File MIME batch has encountered an error.'),
    ]);
  }

  /**
   * Returns count of files in file_managed table.
   */
  public static function count(): int {
    return \Drupal::database()->select('file_managed')->countQuery()->execute()->fetchField();
  }

  /**
   * Batch process callback.
   *
   * @param mixed[] $context
   *   Batch context.
   */
  public static function process(array &$context): void {
    if (!isset($context['results']['processed'])) {
      $context['results']['processed'] = 0;
      $context['results']['updated'] = 0;
      $context['sandbox']['count'] = self::count();
      $context['sandbox']['schemes'] = \Drupal::service('stream_wrapper_manager')->getWrappers(StreamWrapperInterface::LOCAL);
    }
    $files = \Drupal::database()
      ->select('file_managed')
      ->fields('file_managed', ['fid', 'filemime', 'uri'])
      ->orderBy('fid')
      ->range($context['results']['processed'], 1)
      ->execute();
    foreach ($files as $file) {
      // Only operate on local stream URIs, which should represent file names.
      $scheme = \Drupal::service('stream_wrapper_manager')::getScheme($file->uri);
      if ($scheme && isset($context['sandbox']['schemes'][$scheme])) {
        $guesser = \Drupal::service('file.mime_type.guesser');
        // @phpstan-ignore-next-line Compatibility for ancient versions.
        $filemime = $guesser instanceof MimeTypeGuesserInterface ? $guesser->guessMimeType($file->uri) : $guesser->guess($file->uri);
        if ($file->filemime != $filemime) {
          $variables = [
            '%old' => $file->filemime,
            '%new' => $filemime,
            '%url' => $file->uri,
          ];
          // Fully load file object.
          $file = File::load($file->fid);
          $file->filemime = $filemime;
          $file->save();
          $context['results']['updated']++;
          $context['message'] = t('Updated MIME type from %old to %new for %url.', $variables);
          \Drupal::logger('filemime')->notice('Updated MIME type from %old to %new for %url.', $variables);
        }
      }
      $context['results']['processed']++;
      $context['finished'] = $context['results']['processed'] / $context['sandbox']['count'];
    }
  }

  /**
   * Batch finish callback.
   *
   * @param bool $success
   *   Batch status.
   * @param int[] $results
   *   Batch results array.
   */
  public static function finished($success, array $results): void {
    $variables = [
      '@processed' => $results['processed'],
      '@updated' => $results['updated'],
    ];
    if ($success) {
      \Drupal::messenger()->addMessage(t('Processed @processed files and updated @updated files.', $variables));
    }
    else {
      \Drupal::messenger()->addError(t('An error occurred after processing @processed files and updating @updated files.', $variables));
    }
  }

}
