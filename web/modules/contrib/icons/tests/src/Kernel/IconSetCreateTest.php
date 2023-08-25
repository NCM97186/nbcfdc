<?php

namespace Drupal\Tests\group\Kernel;

use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;

/**
 * Tests the creation of Icon Set entities.
 *
 * @group group
 */
class IconSetCreateTest extends EntityKernelTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['icons', 'icons_test_config'];

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * {@inheritdoc}
   * @throws \Exception
   */
  protected function setUp(): void {
    parent::setUp();

    $this->entityTypeManager = $this->container->get('entity_type.manager');

    $this->installEntitySchema('icons');
    $this->installConfig(['icons_test_config']);
  }

  /**
   * Tests special behavior during icon set creation.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function testCreate(): void {
    // Check that the icon set was created and saved properly.
    /** @var \Drupal\icons\Entity\IconSetInterface $icon_set */
    $icon_set = $this->entityTypeManager
      ->getStorage('icon_set')
      ->create([
        'id' => 'dummy',
        'label' => 'Dummy',
        'description' => $this->randomMachineName(),
      ]);

    self::assertTrue($icon_set, 'Icon set was created successfully.');
    self::assertEquals(SAVED_NEW, $icon_set->save(), 'Icon set was saved successfully.');
  }

}
