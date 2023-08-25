<?php

namespace Drupal\Tests\filemime\Functional;

use Drupal\file\Entity\File;
use Drupal\Tests\BrowserTestBase;

/**
 * File MIME tests.
 *
 * @group File MIME
 */
class FileMimeTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * Modules to enable.
   *
   * @var string[]
   */
  protected static $modules = ['filemime', 'file'];

  /**
   * Overrides WebTestBase::setUp().
   */
  protected function setUp(): void {
    parent::setUp();
    $web_user = $this->drupalCreateUser(['administer site configuration']);
    $this->drupalLogin($web_user);
    $this->drupalGet('admin/config/media/filemime');
    $fields = ['types' => 'example/x-does-not-exist filemime'];
    $this->submitForm($fields, 'Save configuration');
  }

  /**
   * Tests that a file MIME is set on the file object.
   */
  public function testFileMime(): void {
    $file = File::create([
      'uid' => 1,
      'filename' => 'druplicon.filemime',
      'uri' => 'public://druplicon.filemime',
      'created' => 1,
      'changed' => 1,
    ]);
    file_put_contents($file->getFileUri(), 'hello world');
    $file->save();
    $this->assertSame('example/x-does-not-exist', $file->getMimeType(), 'File MIME was set correctly.');
    // Test the apply form.
    $this->drupalGet('admin/config/media/filemime/apply');
    $this->submitForm([], 'Apply');
  }

}
