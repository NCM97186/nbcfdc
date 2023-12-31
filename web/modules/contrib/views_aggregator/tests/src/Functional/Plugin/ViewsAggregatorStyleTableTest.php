<?php

namespace Drupal\Tests\views_aggregator\Functional\Plugin;

use Drupal\dynamic_page_cache\EventSubscriber\DynamicPageCacheSubscriber;
use Drupal\Tests\views\Functional\ViewTestBase;
use Drupal\views\Entity\View;

/**
 * Tests the views aggregator result table style plugin.
 *
 * Based on Drupal\Tests\views\Functional\Plugin\StyleTableTest.php.
 *
 * @group views_agregator
 */
class ViewsAggregatorStyleTableTest extends ViewTestBase {

  /**
   * Views used by this test.
   *
   * @var array
   */
  public static $testViews = ['va_test_style_table'];

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    // Modules for core functionality.
    'views',
    'views_aggregator',
    'views_aggregator_test_config',
  ];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * Set to TRUE to strict check all configuration saved.
   *
   * @var bool
   *
   * @see \Drupal\Core\Config\Development\ConfigSchemaChecker
   */
  protected $strictConfigSchema = FALSE;

  /**
   * {@inheritdoc}
   */
  protected function setUp($import_test_views = TRUE, $modules = ['views_aggregator_test_config']): void {
    parent::setUp($import_test_views, $modules);
    $this->enableViewsTestModule();
  }

  /**
   * Test table caption/summary.
   */
  public function testAccessibilitySettings(): void {
    $this->drupalGet('va-test-style-table');

    $result = $this->xpath('//caption/child::text()');
    $this->assertTrue(count($result) > 0, 'The caption appears on the table.');
    $this->assertEquals('caption-text', trim($result[0]->getText()));

    $result = $this->xpath('//summary/child::text()');
    $this->assertTrue(count($result) > 0, 'The summary appears on the table.');
    $this->assertEquals('summary-text', trim($result[0]->getText()));

    // Remove the caption and ensure the caption is not displayed anymore.
    $view = View::load('va_test_style_table');
    $display = &$view->getDisplay('default');
    $display['display_options']['style']['options']['caption'] = '';
    $view->save();

    $this->drupalGet('va-test-style-table');
    $result = $this->xpath('//caption/child::text()');
    $this->assertEquals('', trim($result[0]->getText()), 'Ensure that the caption disappears.');

    // Remove the table summary.
    $display = &$view->getDisplay('default');
    $display['display_options']['style']['options']['summary'] = '';
    $view->save();

    $this->drupalGet('va-test-style-table');
    $result = $this->xpath('//summary/child::text()');
    $this->assertCount(0, $result, 'Ensure that the summary disappears.');
  }

  /**
   * Test table fields in columns.
   */
  public function testFieldInColumns(): void {
    $this->drupalGet('va-test-style-table');

    // Ensure that both columns are in separate tds.
    // Check for class " views-field-job ", because just "views-field-job" won't
    // do: "views-field-job-1" would also contain "views-field-job".
    // @see Drupal\system\Tests\Form\ElementTest::testButtonClasses().
    $result = $this->xpath('//tbody/tr/td[contains(concat(" ", @class, " "), " views-field-job ")]');
    $this->assertTrue(count($result) > 0, 'Ensure there is a td with the class views-field-job');
    $result = $this->xpath('//tbody/tr/td[contains(concat(" ", @class, " "), " views-field-job-1 ")]');
    $this->assertTrue(count($result) > 0, 'Ensure there is a td with the class views-field-job-1');

    // Combine the second job-column with the first one, with ', ' as separator.
    $view = View::load('va_test_style_table');
    $display = &$view->getDisplay('default');
    $display['display_options']['style']['options']['columns']['job_1'] = 'job';
    $display['display_options']['style']['options']['info']['job']['separator'] = ', ';
    $view->save();

    // Ensure that both columns are properly combined.
    $this->drupalGet('va-test-style-table');

    $result = $this->xpath('//tbody/tr/td[contains(concat(" ", @class, " "), " views-field-job views-field-job-1 ")]');
    $this->assertTrue(count($result) > 0, 'Ensure that the job column class names are joined into a single column');

    $result = $this->xpath('//tbody/tr/td[contains(., "Drummer, Drummer")]');
    $this->assertTrue(count($result) > 0, 'Ensure the job column values are joined into a single column');
  }

  /**
   * Test that a number with the value of "0" is displayed in the table.
   */
  public function testNumericFieldVisible(): void {
    // Adds a new datapoint in the views_test_data table to have a person with
    // an age of zero.
    $data_set = $this->dataSet();
    $connection = \Drupal::database();
    $query = $connection->insert('views_test_data')
      ->fields(array_keys($data_set[0]));
    $query->values([
      'name' => 'James McCartney',
      'age' => 0,
      'job' => 'Baby',
      'created' => gmmktime(6, 30, 10, 1, 1, 2000),
      'status' => 1,
    ]);
    $query->execute();

    $this->drupalGet('va-test-style-table');

    $result = $this->xpath('//tbody/tr/td[contains(., "Baby")]');
    $this->assertTrue(count($result) > 0, 'Ensure that the baby is found.');

    $result = $this->xpath('//tbody/tr/td[text()=0]');
    $this->assertTrue(count($result) > 0, 'Ensure that the baby\'s age is shown');
  }

  /**
   * Test that empty columns are hidden when empty_column is set.
   */
  public function testEmptyColumn(): void {
    // Empty the 'job' data.
    \Drupal::database()->update('views_test_data')
      ->fields(['job' => ''])
      ->execute();

    $this->drupalGet('va-test-style-table');

    // Test that only one of the job columns still shows.
    $result = $this->xpath('//thead/tr/th/a[text()="Job"]');
    $this->assertCount(1, $result, 'Ensure that empty column header is hidden.');

    $result = $this->xpath('//tbody/tr/td[contains(concat(" ", @class, " "), " views-field-job-1 ")]');
    $this->assertCount(0, $result, 'Ensure the empty table cells are hidden.');
  }

  /**
   * Tests grouping by a field.
   */
  public function testGrouping(): void {
    /** @var \Drupal\views\ViewEntityInterface $view */
    $view = \Drupal::entityTypeManager()->getStorage('view')->load('va_test_style_table');
    // Get a reference to the display configuration so we can alter some
    // specific style options.
    $display = &$view->getDisplay('default');
    // Set job as the grouping field.
    $display['display_options']['style']['options']['grouping'][0] = [
      'field' => 'job',
      'rendered' => TRUE,
      'rendered_strip' => FALSE,
    ];
    // Clear the caption text, the rendered job field will be used as a caption.
    $display['display_options']['style']['options']['caption'] = '';
    $display['display_options']['style']['options']['summary'] = '';
    $display['display_options']['style']['options']['description'] = '';
    $view->save();

    // Add a record containing unsafe markup to be sure it's filtered out.
    $unsafe_markup = '<script>alert("Rapper");</script>';
    $unsafe_markup_data = [
      'name' => 'Marshall',
      'age' => 42,
      'job' => $unsafe_markup,
      'created' => gmmktime(0, 0, 0, 2, 15, 2001),
      'status' => 1,
    ];
    $database = $this->container->get('database');
    $database->insert('views_test_data')
      ->fields(array_keys($unsafe_markup_data))
      ->values($unsafe_markup_data)
      ->execute();

    $this->drupalGet('va-test-style-table');
    $expected_captions = [
      'Job: Speaker',
      'Job: Songwriter',
      'Job: Drummer',
      'Job: Singer',
      'Job: ' . $unsafe_markup,
    ];

    // Ensure that we don't find the caption containing unsafe markup.
    // Ensure the caption doesn't contain unsafe markup.
    $this->assertSession()->responseNotContains($unsafe_markup);

    // Ensure that all expected captions are found.
    foreach ($expected_captions as $raw_caption) {
      $this->assertSession()->assertEscaped($raw_caption);
    }

    $display = &$view->getDisplay('default');
    // Remove the label from the grouping field.
    $display['display_options']['fields']['job']['label'] = '';
    $view->save();

    $this->drupalGet('va-test-style-table');
    $expected_captions = [
      'Speaker',
      'Songwriter',
      'Drummer',
      'Singer',
      $unsafe_markup,
    ];

    // Ensure that we don't find the caption containing unsafe markup.
    // Ensure the caption doesn't contain unsafe markup.
    $this->assertSession()->responseNotContains($unsafe_markup);

    // Ensure that all expected captions are found.
    foreach ($expected_captions as $raw_caption) {
      $this->assertSession()->assertEscaped($raw_caption);
    }
  }

  /**
   * Tests the cacheability of the table display.
   */
  public function testViewsAggregatorTableCacheability(): void {
    \Drupal::service('module_installer')->uninstall(['page_cache']);

    $url = 'va-test-style-table';
    $this->drupalGet($url);
    $this->assertSession()->statusCodeEquals(200);
    $this->assertEquals('MISS', $this->getSession()->getResponseHeader(DynamicPageCacheSubscriber::HEADER));
    $this->drupalGet($url);
    $this->assertEquals('HIT', $this->getSession()->getResponseHeader(DynamicPageCacheSubscriber::HEADER));
  }

}
