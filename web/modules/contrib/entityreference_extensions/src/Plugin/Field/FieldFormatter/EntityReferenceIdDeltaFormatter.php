<?php

namespace Drupal\entityreference_extensions\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\Plugin\Field\FieldFormatter\EntityReferenceIdFormatter;
use Drupal\entityreference_extensions\EntityReferenceDeltaFilterTrait;

/**
 * Plugin implementation of the 'entity reference ID delta' formatter.
 *
 * @FieldFormatter(
 *   id = "entity_reference_entity_id_delta",
 *   label = @Translation("Entity ID (PLUS)"),
 *   description = @Translation("Display the ID of the referenced entities. With more options (delta, sorting)."),
 *   field_types = {
 *     "entity_reference"
 *   }
 * )
 */
class EntityReferenceIdDeltaFormatter extends EntityReferenceIdFormatter {

  use EntityReferenceDeltaFilterTrait;

}
