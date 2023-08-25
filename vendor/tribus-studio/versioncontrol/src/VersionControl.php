<?php
/** @file
 * Class full of functions to manage deprecated functions throughout the mfd
 * module.
 */
namespace Tribus\VersionControl;


class VersionControl {

  /**
   * Break Down Version
   *
   * Private helper function that takes the version of Drupal that is currently
   * being used and breaks the string down into an array.
   *
   * @return false|string[]
   */
  private function breakDownVersion() {
    //return the version string as an array broken up by the . in the string.
    return explode(".", \Drupal::VERSION);

  }

  /**
   * Set Message
   *
   * Determines which function to call to display a drupal message, either
   * addMessage or drupal_set_message bassed on the version of drupal that is
   * being used.
   *
   * Side note
   * drupal_set_message is deprecated after Drupal 8.5
   *
   * @param $message
   */
  public function setMessage($message){
    $version = $this->breakDownVersion();

    if ($version[0] <= 8) {
      if ($version[1] <= 5) {
        drupal_set_message($message);
      } elseif ($version[1] > 5) {
        \Drupal::Messenger()->addMessage($message);
      }
    } elseif ($version[0] > 8) {
      \Drupal::Messenger()->addMessage($message);
    }

  }
}

