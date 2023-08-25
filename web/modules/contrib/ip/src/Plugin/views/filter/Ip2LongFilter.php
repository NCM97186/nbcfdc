<?php

/**
 * @file
 * Contains \Drupal\ip\Plugin\views\filter\Ip2LongFilter.
 */

namespace Drupal\ip\Plugin\views\filter;

use Drupal\views\Plugin\views\filter\NumericFilter;


/**
 * Filter to handle greater than/less than ip2long filters
 *
 * @ingroup views_filter_handlers
 *
 * @ViewsFilter("ip2long")
 */
class Ip2LongFilter extends NumericFilter {

  function opBetween($field) {
    if (is_numeric(ip2long($this->value['min'])) && is_numeric(ip2long($this->value['max']))) {
      $operator = $this->operator == 'between' ? 'BETWEEN' : 'NOT BETWEEN';
      $this->query->addWhere($this->options['group'], $field, [
        ip2long($this->value['min']),
        ip2long($this->value['max']),
      ], $operator);
    }
    elseif (is_numeric(ip2long($this->value['min']))) {
      $operator = $this->operator == 'between' ? '>=' : '<';
      $this->query->addWhere($this->options['group'], $field, ip2long($this->value['min']), $operator);
    }
    elseif (is_numeric(ip2long($this->value['max']))) {
      $operator = $this->operator == 'between' ? '<=' : '>';
      $this->query->addWhere($this->options['group'], $field, ip2long($this->value['max']), $operator);
    }
  }

  function opSimple($field) {
    $this->query->addWhere($this->options['group'], $field, ip2long($this->value['value']), $this->operator);
  }

  function opEmpty($field) {
    if ($this->operator == 'empty') {
      $operator = "IS NULL";
    }
    else {
      $operator = "IS NOT NULL";
    }

    $this->query->addWhere($this->options['group'], $field, NULL, $operator);
  }

  function op_regex($field) {
    $this->query->addWhere($this->options['group'], $field, ip2long($this->value['value']), 'RLIKE');
  }
}
