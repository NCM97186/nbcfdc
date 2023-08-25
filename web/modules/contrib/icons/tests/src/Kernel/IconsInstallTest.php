<?php

namespace Drupal\Tests\group\Kernel;

use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;

/**
 * Tests the creation of icon set entities during extension install.
 *
 * @group group
 */
class IconsInstallTest extends EntityKernelTestBase {

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
   */
  public function testInstall(): void {
    // Check that the icon set was created and saved properly.
    /** @var \Drupal\icons\Entity\IconSetInterface $icon_set */
    $group_type = $this->entityTypeManager
      ->getStorage('icon_set')
      ->load('default');

    self::assertNotNull($group_type, 'Icon set was loaded successfully.');
  }

}
