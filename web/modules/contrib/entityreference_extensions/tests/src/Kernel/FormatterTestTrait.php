<?php

namespace Drupal\Tests\entityreference_extensions\Kernel;

use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\node\Entity\Node;
use Drupal\node\Entity\NodeType;

/**
 * Trait for testing entityreference_extensions formatters.
 *
 * Takes care of creating a test nodes.
 */
trait FormatterTestTrait {

  /**
   * An array of test nodes.
   *
   * @var Drupal\node\Entity\Node[]
   */
  public $nodes;

  /**
   * Helper function to create a node type that can be used for testing.
   */
  protected function createTestNodeType() {
    $node_type = NodeType::create(['type' => 'test_node_type', 'name' => 'Test Node Type']);
    $node_type->save();
    // A string field.
    $field_storage = FieldStorageConfig::create([
      'field_name' => 'field_string',
      'entity_type' => 'node',
      'type' => 'string',
    ]);
    $field_storage->save();
    $instance = FieldConfig::create([
      'field_storage' => $field_storage,
      'bundle' => 'test_node_type',
      'label' => 'String',
    ]);
    $instance->save();
    // An entity reference field we will sort.
    $field_storage = FieldStorageConfig::create([
      'field_name' => 'field_other_nodes',
      'entity_type' => 'node',
      'type' => 'entity_reference',
      'cardinality' => '-1',
    ]);
    $field_storage->save();
    $instance = FieldConfig::create([
      'field_storage' => $field_storage,
      'bundle' => 'test_node_type',
      'label' => 'Other Nodes',
    ]);
    $instance->save();
  }

  /**
   * Helper function to create test nodes.
   *
   * @return Drupal\node\Entity\Node[]
   *   An array of nodes we can play with.
   */
  protected function createTestNodes() {
    $settings = [
      [
        'field_string' => 'The first node created.',
        'title' => 'Node Number 1',
        'sticky' => FALSE,
        'status' => 1,
      ],
      [
        'field_string' => 'The second node created.',
        'field_other_nodes' => [
          'target_id' => 1,
        ],
        'title' => 'Another Node: Node 2',
        'sticky' => TRUE,
        'status' => 1,
      ],
      [
        'field_string' => 'The third node created.',
        'field_other_nodes' => [
          ['target_id' => 1],
          ['target_id' => 2],
        ],
        'title' => 'Third Node',
        'sticky' => TRUE,
        'status' => 1,
      ],
      [
        'field_string' => 'The fourth node created.',
        'field_other_nodes' => [
          ['target_id' => 2],
          ['target_id' => 1],
          ['target_id' => 3],
        ],
        'title' => 'Node the Fourth',
        'sticky' => FALSE,
        'status' => 1,
      ],
      [
        'field_string' => 'The fifth node created.',
        'field_other_nodes' => [
          ['target_id' => 4],
          ['target_id' => 1],
          ['target_id' => 3],
          ['target_id' => 2],
        ],
        'title' => 'High Five',
        'status' => 1,
      ],
    ];
    $nodes = [];
    foreach ($settings as $setting) {
      $node = Node::create(['type' => 'test_node_type']);
      foreach ($setting as $key => $val) {
        $node->set($key, $val);
      }
      $node->save();
      $nodes[] = $node;
    }
    return $nodes;
  }

}
