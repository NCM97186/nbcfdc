# CONTENTS OF THIS FILE

 * Introduction
 * Requirements
 * Recommended modules
 * Installation
 * Configuration
 * Usage example
   * Plugin
   * Twig
   * Working example
 * Troubleshooting
 * FAQ
 * Maintainers
 * Supporting organizations


# INTRODUCTION

This module provides a plugin to allow developers to create computed field via
the Drupal Plugin API, and a field type `computed_render_array`.

 * For a full description of the module, visit the project page:
 https://www.drupal.org/project/computed_field_plugin

 * To submit bug reports and feature suggestions, or track changes:
 https://www.drupal.org/project/issues/search/computed_field_plugin


# REQUIREMENTS

No special requirements.


# RECOMMENDED MODULES

 * No specific recommendations, you can use this module as is.

 * Similar module: https://www.drupal.org/project/computed_field. The approach
 is different, that's why we made another one.

 * Inspiration for this module: https://www.drupal.org/project/extra_field has a
  similar use of plugins, but for extra fields.


# INSTALLATION

Install as you would normally install a contributed Drupal module.
Visit https://www.drupal.org/documentation/install/modules-themes/modules-7 for
further information.


# CONFIGURATION

The module has no menu or modifiable settings. There is no configuration.

When enabled, the module will provide a way to create computed fields via the
Plugin API and a new field type called `computed_render_array`. Remove any
custom code based on these before disabling the module.


# USAGE EXAMPLE

## Plugin

```php
<?php

namespace Drupal\my_module\Plugin\ComputedField;

use Drupal\computed_field_plugin\Traits\ComputedSingleItemTrait;
use Drupal\Core\Field\FieldItemList;

/**
 * Class TestComputedField.
 *
 * @ComputedField(
 *   id = "test_computed_field",
 *   label = @Translation("Test computed field"),
 *   type = "computed_render_array",
 *   entity_types = {"node"},
 *   bundles = {"page"}
 * )
 */
class TestComputedField extends FieldItemList {

  use ComputedSingleItemTrait;

  /**
   * Handle single value.
   *
   * @return mixed
   *   Returns the computed value.
   */
  protected function singleComputeValue() {
    return [
      '#markup' => '<p>Lorem ipsum dolor sit amet</p>',
    ];
  }

}
```

The entity_types and bundles annotation properties expect an array, leave empty
to allow all. The label annotation property defines the field label as for
any normal field.

## Twig

Example in node.html.twig:

```twig
{{ content.test_computed_field }}
```

Simply use the plugin id like a field machine name.

## Working example

I have a very simple working example I made to update this doc: a word counter
for the body.

File `MY_MODULE/src/Plugin/ComputedField/DemoPluginComputedField.php`

```php
<?php

namespace Drupal\MY_MODULE\Plugin\ComputedField;

use Drupal\computed_field_plugin\Annotation\ComputedField;
use Drupal\computed_field_plugin\Traits\ComputedSingleItemTrait;
use Drupal\Core\Annotation\Translation;
use Drupal\Core\Field\FieldItemList;

/**
 * Class DemoPluginComputedField.
 *
 * @ComputedField(
 *   id = "demo_plugin_computed_field",
 *   label = @Translation("Demo plugin computed field"),
 *   type = "computed_render_array",
 *   entity_types = {"node"},
 *   bundles = {"page"}
 * )
 */
class DemoPluginComputedField extends FieldItemList {

  use ComputedSingleItemTrait;

  /**
   * Handle single value.
   *
   * @return mixed
   *   Returns the computed value.
   */
  protected function singleComputeValue() {
    $body = $this->getEntity()->get('body')->value;

    return [
      '#theme' => 'custom_computed_field_word_count',
      '#word_count' => str_word_count($body),
    ];
  }

}
```

Then in the .module file I just need my hook_theme:

```php
/**
 * Implements hook_theme().
 */
function MY_MODULE_theme($existing, $type, $theme, $path) {
  $themes = [];
  $themes['custom_computed_field_word_count'] = [
    'variables' => [
      'word_count' => '',
    ],
  ];
  return $themes;
}
```

And for twig `MY_MODULE/templates/custom-computed-field-word-count.html.twig`:

```twig
<p>{{ 'Word count: @word_count'|t({'@word_count': word_count}) }}</p>
```


# TROUBLESHOOTING

Layout Builder does not support extra fields or computed fields as a whole. See
[this core issue](https://www.drupal.org/project/drupal/issues/3034979) or [our
issue](https://www.drupal.org/project/computed_field_plugin/issues/3093367) for
updates, but at the moment it is not possible to use both together.


# FAQ

Q: Do I need this module to create computed fields?

A: No, you do not need a contrib module for this! This module's goal is to make
 it easier by using the plugin API (like you would to create, say, a block).
 Here is the documentation to create computed fields on a standard D8 instance:
 https://www.drupal.org/docs/8/api/entity-api/dynamicvirtual-field-values-using-computed-field-property-classes


# MAINTAINERS

Current maintainers:
 * [Marine Gandy (Mupsi)](https://www.drupal.org/u/mupsi)
 * [Nicolas Loye (nicoloye)](https://www.drupal.org/u/nicoloye)

# SUPPORTING ORGANIZATIONS

* [ecedi](https://www.drupal.org/ecedi) sponsored the initial development
* [Actency](https://www.drupal.org/actency) currently sponsors the maintenance
