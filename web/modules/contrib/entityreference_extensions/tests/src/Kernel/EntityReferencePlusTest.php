<?php

namespace Drupal\Tests\entityreference_extensions\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\Tests\user\Traits\UserCreationTrait;
use PHPUnit\Framework\Assert;

/**
 * Class for testing formatters that use the Plus formatters.
 *
 * The bulk of these tests use the id formatter. They are
 * primarily intended to test the sorting functionality.
 * The other two formatters have one test each
 * just to make sure there are no fatal errors.
 *
 * @group entityreference_extensions
 */
class EntityReferencePlusTest extends KernelTestBase {

  use FormatterTestTrait;
  use UserCreationTrait;

  /**
   * The modules to load to run the test.
   *
   * @var array
   */
  public static $modules = [
    'field',
    'system',
    'user',
    'node',
    'entityreference_extensions',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->installConfig(['system', 'field']);
    $this->installSchema('user', ['users_data']);
    $this->installEntitySchema('user');
    $this->installEntitySchema('node');
    $this->installEntitySchema('node_type');
    $this->setUpCurrentUser(['uid' => 99], ['access content']);
    $this->createTestNodeType();
    $this->nodes = $this->createTestNodes();
  }

  /**
   * Test sorting by title.
   */
  public function testTitleSort() {
    $render = $this->nodes[4]->field_other_nodes->view([
      'type' => 'entity_reference_entity_id_delta',
      'label' => 'hidden',
      'settings' => [
        'limit' => [
          'number' => '',
          'offset' => '',
          'reverse' => FALSE,
          'limit_before_sort' => TRUE,
        ],
        'sort' => [
          'field' => 'title',
          'asc' => TRUE,
        ],
      ],
    ]);
    // Another Node: Node 2.
    Assert::assertEquals(2, $render[0]['#plain_text']);
    // Node Number 1.
    Assert::assertEquals(1, $render[1]['#plain_text']);
    // Node the Fourth.
    Assert::assertEquals(4, $render[2]['#plain_text']);
    // Third Node.
    Assert::assertEquals(3, $render[3]['#plain_text']);

    // And backwards...
    $render = $this->nodes[4]->field_other_nodes->view([
      'type' => 'entity_reference_entity_id_delta',
      'label' => 'hidden',
      'settings' => [
        'limit' => [
          'number' => '',
          'offset' => '',
          'reverse' => FALSE,
          'limit_before_sort' => TRUE,
        ],
        'sort' => [
          'field' => 'title',
          'asc' => FALSE,
        ],
      ],
    ]);
    Assert::assertEquals(3, $render[0]['#plain_text']);
    Assert::assertEquals(4, $render[1]['#plain_text']);
    Assert::assertEquals(1, $render[2]['#plain_text']);
    Assert::assertEquals(2, $render[3]['#plain_text']);

    // Try with offset before ascending sort.
    $render = $this->nodes[4]->field_other_nodes->view([
      'type' => 'entity_reference_entity_id_delta',
      'label' => 'hidden',
      'settings' => [
        'limit' => [
          'number' => '',
          'offset' => '1',
          'reverse' => FALSE,
          'limit_before_sort' => TRUE,
        ],
        'sort' => [
          'field' => 'title',
          'asc' => TRUE,
        ],
      ],
    ]);
    Assert::assertEquals(2, $render[0]['#plain_text']);
    Assert::assertEquals(1, $render[1]['#plain_text']);
    Assert::assertEquals(3, $render[2]['#plain_text']);

    // Try with offset before descending sort.
    $render = $this->nodes[4]->field_other_nodes->view([
      'type' => 'entity_reference_entity_id_delta',
      'label' => 'hidden',
      'settings' => [
        'limit' => [
          'number' => '',
          'offset' => '1',
          'reverse' => FALSE,
          'limit_before_sort' => TRUE,
        ],
        'sort' => [
          'field' => 'title',
          'asc' => FALSE,
        ],
      ],
    ]);
    Assert::assertEquals(3, $render[0]['#plain_text']);
    Assert::assertEquals(1, $render[1]['#plain_text']);
    Assert::assertEquals(2, $render[2]['#plain_text']);

    // Try with offset after ascending sort.
    $render = $this->nodes[4]->field_other_nodes->view([
      'type' => 'entity_reference_entity_id_delta',
      'label' => 'hidden',
      'settings' => [
        'limit' => [
          'number' => '',
          'offset' => '1',
          'reverse' => FALSE,
          'limit_before_sort' => FALSE,
        ],
        'sort' => [
          'field' => 'title',
          'asc' => TRUE,
        ],
      ],
    ]);
    Assert::assertEquals(1, $render[0]['#plain_text']);
    Assert::assertEquals(4, $render[1]['#plain_text']);
    Assert::assertEquals(3, $render[2]['#plain_text']);

    // Limit before ascending sort.
    $render = $this->nodes[4]->field_other_nodes->view([
      'type' => 'entity_reference_entity_id_delta',
      'label' => 'hidden',
      'settings' => [
        'limit' => [
          'number' => '3',
          'offset' => '',
          'reverse' => FALSE,
          'limit_before_sort' => TRUE,
        ],
        'sort' => [
          'field' => 'title',
          'asc' => TRUE,
        ],
      ],
    ]);
    // Node 2 has the largest delta. It's gone.
    Assert::assertEquals(1, $render[0]['#plain_text']);
    Assert::assertEquals(4, $render[1]['#plain_text']);
    Assert::assertEquals(3, $render[2]['#plain_text']);

    // Limit in reverse before ascending sort.
    $render = $this->nodes[4]->field_other_nodes->view([
      'type' => 'entity_reference_entity_id_delta',
      'label' => 'hidden',
      'settings' => [
        'limit' => [
          'number' => '3',
          'offset' => '',
          'reverse' => TRUE,
          'limit_before_sort' => TRUE,
        ],
        'sort' => [
          'field' => 'title',
          'asc' => TRUE,
        ],
      ],
    ]);
    // Node 4 has the smallest delta. It's gone.
    Assert::assertEquals(2, $render[0]['#plain_text']);
    Assert::assertEquals(1, $render[1]['#plain_text']);
    Assert::assertEquals(3, $render[2]['#plain_text']);

    // Limit after ascending sort.
    $render = $this->nodes[4]->field_other_nodes->view([
      'type' => 'entity_reference_entity_id_delta',
      'label' => 'hidden',
      'settings' => [
        'limit' => [
          'number' => '3',
          'offset' => '',
          'reverse' => FALSE,
          'limit_before_sort' => FALSE,
        ],
        'sort' => [
          'field' => 'title',
          'asc' => TRUE,
        ],
      ],
    ]);
    // Node 3 has the name closest to Z. It's gone.
    Assert::assertEquals(2, $render[0]['#plain_text']);
    Assert::assertEquals(1, $render[1]['#plain_text']);
    Assert::assertEquals(4, $render[2]['#plain_text']);

    // Limit in reverse after ascending sort.
    $render = $this->nodes[4]->field_other_nodes->view([
      'type' => 'entity_reference_entity_id_delta',
      'label' => 'hidden',
      'settings' => [
        'limit' => [
          'number' => '3',
          'offset' => '',
          'reverse' => TRUE,
          'limit_before_sort' => FALSE,
        ],
        'sort' => [
          'field' => 'title',
          'asc' => TRUE,
        ],
      ],
    ]);
    // Node 2 has the name closest to A. It's gone.
    Assert::assertEquals(1, $render[0]['#plain_text']);
    Assert::assertEquals(4, $render[1]['#plain_text']);
    Assert::assertEquals(3, $render[2]['#plain_text']);

    // Limit with offset before ascending sort.
    $render = $this->nodes[4]->field_other_nodes->view([
      'type' => 'entity_reference_entity_id_delta',
      'label' => 'hidden',
      'settings' => [
        'limit' => [
          'number' => '2',
          'offset' => '1',
          'reverse' => FALSE,
          'limit_before_sort' => TRUE,
        ],
        'sort' => [
          'field' => 'title',
          'asc' => TRUE,
        ],
      ],
    ]);
    // Just 1 and 3 get sorted.
    Assert::assertEquals(1, $render[0]['#plain_text']);
    Assert::assertEquals(3, $render[1]['#plain_text']);

    // Limit with offset after ascending sort.
    $render = $this->nodes[4]->field_other_nodes->view([
      'type' => 'entity_reference_entity_id_delta',
      'label' => 'hidden',
      'settings' => [
        'limit' => [
          'number' => '2',
          'offset' => '1',
          'reverse' => FALSE,
          'limit_before_sort' => FALSE,
        ],
        'sort' => [
          'field' => 'title',
          'asc' => TRUE,
        ],
      ],
    ]);
    Assert::assertEquals(1, $render[0]['#plain_text']);
    Assert::assertEquals(4, $render[1]['#plain_text']);

    // Limit with offset in reverse before descending sort.
    $render = $this->nodes[4]->field_other_nodes->view([
      'type' => 'entity_reference_entity_id_delta',
      'label' => 'hidden',
      'settings' => [
        'limit' => [
          'number' => '2',
          'offset' => '2',
          'reverse' => TRUE,
          'limit_before_sort' => TRUE,
        ],
        'sort' => [
          'field' => 'title',
          'asc' => FALSE,
        ],
      ],
    ]);
    // Just 1 and 4 get sorted.
    Assert::assertEquals(4, $render[0]['#plain_text']);
    Assert::assertEquals(1, $render[1]['#plain_text']);

    // Limit with offset in reverse after descending sort.
    $render = $this->nodes[4]->field_other_nodes->view([
      'type' => 'entity_reference_entity_id_delta',
      'label' => 'hidden',
      'settings' => [
        'limit' => [
          'number' => '2',
          'offset' => '2',
          'reverse' => TRUE,
          'limit_before_sort' => FALSE,
        ],
        'sort' => [
          'field' => 'title',
          'asc' => FALSE,
        ],
      ],
    ]);
    Assert::assertEquals(3, $render[0]['#plain_text']);
    Assert::assertEquals(4, $render[1]['#plain_text']);
  }

  /**
   * Test sorting by sticky.
   */
  public function testStickySort() {
    $render = $this->nodes[4]->field_other_nodes->view([
      'type' => 'entity_reference_entity_id_delta',
      'label' => 'hidden',
      'settings' => [
        'limit' => [
          'number' => '',
          'offset' => '',
          'reverse' => FALSE,
          'limit_before_sort' => TRUE,
        ],
        'sort' => [
          'field' => 'sticky',
          'asc' => FALSE,
        ],
      ],
    ]);
    // Node 3 is sticky with delta 2.
    Assert::assertEquals(3, $render[0]['#plain_text']);
    // Node 2 is sticky with delta 3.
    Assert::assertEquals(2, $render[1]['#plain_text']);
    // Node 4 is not sticky with delta 0.
    Assert::assertEquals(4, $render[2]['#plain_text']);
    // Node 1 is not sticky with delta 1.
    Assert::assertEquals(1, $render[3]['#plain_text']);

    // Just for fun, check asc for sticky.
    $render = $this->nodes[4]->field_other_nodes->view([
      'type' => 'entity_reference_entity_id_delta',
      'label' => 'hidden',
      'settings' => [
        'limit' => [
          'number' => '',
          'offset' => '',
          'reverse' => FALSE,
          'limit_before_sort' => TRUE,
        ],
        'sort' => [
          'field' => 'sticky',
          'asc' => TRUE,
        ],
      ],
    ]);
    // Node 4 is not sticky with delta 0.
    Assert::assertEquals(4, $render[0]['#plain_text']);
    // Node 1 is not sticky with delta 1.
    Assert::assertEquals(1, $render[1]['#plain_text']);
    // Node 3 is sticky with delta 2.
    Assert::assertEquals(3, $render[2]['#plain_text']);
    // Node 2 is sticky with delta 3.
    Assert::assertEquals(2, $render[3]['#plain_text']);
  }

  /**
   * Test sorting by delta.
   */
  public function testDeltaSort() {
    $render = $this->nodes[4]->field_other_nodes->view([
      'type' => 'entity_reference_entity_id_delta',
      'label' => 'hidden',
      'settings' => [
        'limit' => [
          'number' => '',
          'offset' => '',
          'reverse' => FALSE,
          'limit_before_sort' => TRUE,
        ],
        'sort' => [
          'field' => '',
          'asc' => TRUE,
        ],
      ],
    ]);
    // Just go by delta.
    Assert::assertEquals(4, $render[0]['#plain_text']);
    Assert::assertEquals(1, $render[1]['#plain_text']);
    Assert::assertEquals(3, $render[2]['#plain_text']);
    Assert::assertEquals(2, $render[3]['#plain_text']);

    // Test that desc works for delta.
    $render = $this->nodes[4]->field_other_nodes->view([
      'type' => 'entity_reference_entity_id_delta',
      'label' => 'hidden',
      'settings' => [
        'limit' => [
          'number' => '',
          'offset' => '',
          'reverse' => FALSE,
          'limit_before_sort' => TRUE,
        ],
        'sort' => [
          'field' => '',
          'asc' => FALSE,
        ],
      ],
    ]);
    Assert::assertEquals(2, $render[0]['#plain_text']);
    Assert::assertEquals(3, $render[1]['#plain_text']);
    Assert::assertEquals(1, $render[2]['#plain_text']);
    Assert::assertEquals(4, $render[3]['#plain_text']);
  }

  /**
   * Test sorting by an entity reference field.
   *
   * Use cases are few, I would think, but let's add a test.
   * This tests behavior of empty fields too. So it is a useful
   * test regardless. It also makes sure that we can handle
   * sorting for any main property name and by a multivalued
   * field. Holy cow this test has a lot going on.
   */
  public function testTargetIdSort() {
    $render = $this->nodes[4]->field_other_nodes->view([
      'type' => 'entity_reference_entity_id_delta',
      'label' => 'hidden',
      'settings' => [
        'limit' => [
          'number' => '',
          'offset' => '',
          'reverse' => FALSE,
          'limit_before_sort' => TRUE,
        ],
        'sort' => [
          'field' => 'field_other_nodes',
          'asc' => TRUE,
        ],
      ],
    ]);
    // It's a sort by the nid of the first reference.
    Assert::assertEquals(3, $render[0]['#plain_text']);
    Assert::assertEquals(2, $render[1]['#plain_text']);
    Assert::assertEquals(4, $render[2]['#plain_text']);
    // No reference. Should be last.
    Assert::assertEquals(1, $render[3]['#plain_text']);

    // Test that desc works for delta.
    $render = $this->nodes[4]->field_other_nodes->view([
      'type' => 'entity_reference_entity_id_delta',
      'label' => 'hidden',
      'settings' => [
        'limit' => [
          'number' => '',
          'offset' => '',
          'reverse' => FALSE,
          'limit_before_sort' => TRUE,
        ],
        'sort' => [
          'field' => 'field_other_nodes',
          'asc' => FALSE,
        ],
      ],
    ]);
    Assert::assertEquals(4, $render[0]['#plain_text']);
    // Node 3 should come before Node 2 because it has lower delta.
    // Sort descending still respects delta in case of tie.
    Assert::assertEquals(3, $render[1]['#plain_text']);
    Assert::assertEquals(2, $render[2]['#plain_text']);
    // No reference. Still should be last.
    Assert::assertEquals(1, $render[3]['#plain_text']);
  }

  /**
   * Test for expected behavior in 4-way tie.
   */
  public function testFourWayTie() {
    $render = $this->nodes[4]->field_other_nodes->view([
      'type' => 'entity_reference_entity_id_delta',
      'label' => 'hidden',
      'settings' => [
        'limit' => [
          'number' => '',
          'offset' => '',
          'reverse' => FALSE,
          'limit_before_sort' => TRUE,
        ],
        'sort' => [
          'field' => 'status',
          'asc' => TRUE,
        ],
      ],
    ]);
    // Just go by delta.
    Assert::assertEquals(4, $render[0]['#plain_text']);
    Assert::assertEquals(1, $render[1]['#plain_text']);
    Assert::assertEquals(3, $render[2]['#plain_text']);
    Assert::assertEquals(2, $render[3]['#plain_text']);

    $render = $this->nodes[4]->field_other_nodes->view([
      'type' => 'entity_reference_entity_id_delta',
      'label' => 'hidden',
      'settings' => [
        'limit' => [
          'number' => '',
          'offset' => '',
          'reverse' => FALSE,
          'limit_before_sort' => TRUE,
        ],
        'sort' => [
          'field' => 'status',
          'asc' => FALSE,
        ],
      ],
    ]);
    // Since they all tie, we expect delta to be respected again.
    // Even descending, ties are broken by delta, with lower
    // delta coming first.
    Assert::assertEquals(4, $render[0]['#plain_text']);
    Assert::assertEquals(1, $render[1]['#plain_text']);
    Assert::assertEquals(3, $render[2]['#plain_text']);
    Assert::assertEquals(2, $render[3]['#plain_text']);
  }

  /**
   * Test a bogus field.
   *
   * This could happen if there are multiple bundles allowed.
   */
  public function testBogusSort() {
    $render = $this->nodes[4]->field_other_nodes->view([
      'type' => 'entity_reference_entity_id_delta',
      'label' => 'hidden',
      'settings' => [
        'limit' => [
          'number' => '',
          'offset' => '',
          'reverse' => FALSE,
          'limit_before_sort' => TRUE,
        ],
        'sort' => [
          'field' => 'field_bogus',
          'asc' => TRUE,
        ],
      ],
    ]);
    // Just go by delta.
    Assert::assertEquals(4, $render[0]['#plain_text']);
    Assert::assertEquals(1, $render[1]['#plain_text']);
    Assert::assertEquals(3, $render[2]['#plain_text']);
    Assert::assertEquals(2, $render[3]['#plain_text']);

    $render = $this->nodes[4]->field_other_nodes->view([
      'type' => 'entity_reference_entity_id_delta',
      'label' => 'hidden',
      'settings' => [
        'limit' => [
          'number' => '',
          'offset' => '',
          'reverse' => FALSE,
          'limit_before_sort' => TRUE,
        ],
        'sort' => [
          'field' => 'field_bogus',
          'asc' => FALSE,
        ],
      ],
    ]);
    // Since they all tie, we expect delta to be respected again.
    // Even descending, ties are broken by delta, with lower
    // delta coming first.
    Assert::assertEquals(4, $render[0]['#plain_text']);
    Assert::assertEquals(1, $render[1]['#plain_text']);
    Assert::assertEquals(3, $render[2]['#plain_text']);
    Assert::assertEquals(2, $render[3]['#plain_text']);
  }

  /**
   * Test the label formatter.
   *
   * Just a light test to make sure nothing catastrophic happens.
   * We are confident the sorting works.
   */
  public function testLabelFormatter() {
    $render = $this->nodes[4]->field_other_nodes->view([
      'type' => 'entity_reference_label_delta',
      'label' => 'hidden',
      'settings' => [
        'link' => FALSE,
        'limit' => [
          'number' => '',
          'offset' => '',
          'reverse' => FALSE,
          'limit_before_sort' => TRUE,
        ],
        'sort' => [
          'field' => 'title',
          'asc' => TRUE,
        ],
      ],
    ]);
    Assert::assertEquals('Another Node: Node 2', $render[0]['#plain_text']);
    Assert::assertEquals('Node Number 1', $render[1]['#plain_text']);
    Assert::assertEquals('Node the Fourth', $render[2]['#plain_text']);
    Assert::assertEquals('Third Node', $render[3]['#plain_text']);
  }

  /**
   * Test the rendered entity formatter.
   *
   * Just a light test to make sure nothing catastrophic happens.
   * We are confident the sorting works.
   */
  public function testRenderedEntityFormatter() {
    $render = $this->nodes[4]->field_other_nodes->view([
      'type' => 'entity_reference_entity_view_delta',
      'label' => 'hidden',
      'settings' => [
        'view_mode' => 'default',
        'limit' => [
          'number' => '',
          'offset' => '',
          'reverse' => FALSE,
          'limit_before_sort' => TRUE,
        ],
        'sort' => [
          'field' => 'nid',
          'asc' => TRUE,
        ],
      ],
    ]);
    Assert::assertEquals(1, $render[0]['#node']->id());
    Assert::assertEquals(2, $render[1]['#node']->id());
    Assert::assertEquals(3, $render[2]['#node']->id());
    Assert::assertEquals(4, $render[3]['#node']->id());
  }

}
