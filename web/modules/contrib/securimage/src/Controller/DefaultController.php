<?php /**
 * @file
 * Contains \Drupal\securimage\Controller\DefaultController.
 */

namespace Drupal\securimage\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\securimage\SecurimageMiddleware;

/**
 * Default controller for the securimage module.
 */
class DefaultController extends ControllerBase {

  const SECURIMAGE_SOLUTION_UNSET = '.';

  public static function securimage_image() {
    return static::generate('show');
  }

  public static function securimage_audio() {
    return static::generate('outputAudioFile');
  }

  private static function generate($func) {
    if ($old = SecurimageMiddleware::getSolution()) {
      // If output buffering is on: discard current content and disable further buffering
      if (ob_get_level()) {
        ob_end_clean();
      }

      // Set 'no_exit' to prevent the Securimage library from exit()ing.
      $vars = ['no_exit' => TRUE];
      $vars['captchaId'] = !empty($_GET['id']) ? $_GET['id'] : \Securimage::generateCaptchaId();
      if ($old->solution != self::SECURIMAGE_SOLUTION_UNSET && !isset($_GET['new'])) {
        $vars['display_value'] = $vars['code_display'] = $old->solution;
        $vars['code'] = mb_strtolower($old->solution);
      }
      $securimage = SecurimageMiddleware::getInstance($vars);
      if (isset($_GET['new'])) {
        $securimage->createCode();
      }
      // Generate the image or audio.
      if ($func == 'outputAudioFile' && SecurimageMiddleware::getStoredSettings()['_module']['use_audio'] && !empty(\Securimage::$lame_binary_path) && is_executable(\Securimage::$lame_binary_path)) {
        $securimage->outputAudioFile('mp3');
      }
      else {
        $securimage->$func();
      }
      // Update the value stored in the database.
      if ($old->solution == self::SECURIMAGE_SOLUTION_UNSET || isset($_GET['new'])) {
        $prefixes = \Drupal::database()->getUnprefixedTablesMap();
        $table_name = isset($prefixes[$securimage->database_table]) ? $prefixes[$securimage->database_table] : $securimage->database_table;
        $code = \Drupal::database()
          ->select($table_name, 't')
          ->fields('t', ['code'])
          ->condition('id', $vars['captchaId'])
          ->execute()
          ->fetchField();
        if ($code) {
          \Drupal::database()->update('captcha_sessions')
            ->condition('csid', $old->csid)
            ->fields(['status' => CAPTCHA_STATUS_UNSOLVED, 'solution' => $code])
            ->execute();
        }
      }
    }
    exit();
  }

}
