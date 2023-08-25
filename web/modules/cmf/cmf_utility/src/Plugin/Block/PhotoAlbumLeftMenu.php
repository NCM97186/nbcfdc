<?php /**
 * @file
 * Contains \Drupal\cmf_utility\Plugin\Block\PhotoAlbumLeftMenu.
 */

namespace Drupal\cmf_utility\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides the PhotoAlbumLeftMenu block.
 *
 * @Block(
 *   id = "cmf_utility_photo_album_left_menu",
 *   admin_label = @Translation("Content section category")
 * )
 */
class PhotoAlbumLeftMenu extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    /**
     * @FIXME
     * hook_block_view() has been removed in Drupal 8. You should move your
     * block's view logic into this method and delete cmf_utility_block_view()
     * as soon as possible!
     */
    return cmf_utility_block_view('photo_album_left_menu');
  }

  
}
