<?php

namespace Drupal\entityreference_extensions\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\Plugin\Field\FieldFormatter\EntityReferenceLabelFormatter;
use Drupal\entityreference_extensions\EntityReferenceDeltaFilterTrait;

/**
 * Plugin implementation of the 'entity reference label delta' formatter.
 *
 * @FieldFormatter(
 *   id = "entity_reference_label_delta",
 *   label = @Translation("Label (PLUS)"),
 *   description = @Translation("Display the label of the referenced entities. With more options (delta, sorting)."),
 *   field_types = {
 *     "entity_reference"
 *   }
 * )
 */
class EntityReferenceLabelDeltaFormatter extends EntityReferenceLabelFormatter {

  use EntityReferenceDeltaFilterTrait;

}
