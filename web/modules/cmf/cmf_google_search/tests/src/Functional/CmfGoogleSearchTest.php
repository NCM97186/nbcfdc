<?php

namespace Drupal\Tests\cmf_google_search\Functional;

use Drupal\Core\Url;
use Drupal\Tests\BrowserTestBase;

/**
 * Tests if the search form submit redirects correctly.
 *
 * @group cmf_google_search
 */
class CmfGoogleSearchTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['block', 'cmf_google_search'];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->drupalLogin($this->drupalCreateUser(['access gse search page']));
    $this->placeBlock('cmf_google_search_block');
  }

  /**
   * Tests searching redirects as expected.
   */
  public function testSearch() {
    $this->drupalGet('<front>');
    $this->submitForm([
      's' => 'bananas',
    ], 'go');
    $url = Url::fromRoute('cmf_google_search.search_page', [], [
      'query' => [
        's' => 'bananas',
      ],
    ])->setAbsolute()->toString();
    $this->assertEquals($url, $this->getSession()->getCurrentUrl());
    $this->assertSession()->fieldValueEquals('s', 'bananas');
  }

}
