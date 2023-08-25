<?php

/**
 * @file
 * Provides ip_tracker view fields and filters.
 */

/**
 * Implements hook_views_data().
 */
function ip_views_data() {
  $data = [];

  // Table: IP tracker:
  $data['ip_tracker']['table']['group'] = t('IP Tracker');
  $data['ip_tracker']['table']['base'] = [
    'field' => 'uid',
    'title' => t('IP Tracker (user) table.'),
    'help' => t('IP Tracker (user) table.'),
    'weight' => -10,
  ];

  // Uid field
  $data['ip_tracker']['uid'] = [
    'title' => t('User id'),
    'help' => t('Unique id of user'),
    'relationship' => [
      'base' => 'users_field_data',
      'base field' => 'uid',
      'field' => 'uid',
      'id' => 'standard',
      'label' => t('User uid'),
    ],
    'field' => [
      'id' => 'numeric',
    ],
    'sort' => [
      'id' => 'numeric',
    ],
    'filter' => [
      'id' => 'numeric',
    ],
    'argument' => [
      'id' => 'numeric',
    ],
  ];

  // IP field
  $data['ip_tracker']['ip'] = [
    'title' => t('IP Address'),
    'help' => t('IP address of user.'),
    'field' => [
      'id' => 'long2ip',
    ],
    'sort' => [
      'id' => 'numeric',
    ],
    'filter' => [
      'id' => 'ip2long',
    ],
    'argument' => [
      'id' => 'ip2long',
    ],
  ];

  // Visit
  $data['ip_tracker']['visits'] = [
    'title' => t('Visits'),
    'help' => t('Total visits'),
    'field' => [
      'id' => 'numeric',
    ],
    'sort' => [
      'id' => 'numeric',
    ],
    'filter' => [
      'id' => 'numeric',
    ],
    'argument' => [
      'id' => 'numeric',
    ],
  ];

  // First visit
  $data['ip_tracker']['first_visit'] = [
    'title' => t('First visit'),
    'help' => t('First visit: registration/login.'),
    'field' => [
      'id' => 'date',
    ],
    'sort' => [
      'id' => 'date',
    ],
    'filter' => [
      'id' => 'date',
    ],
    'argument' => [
      'id' => 'date',
    ],
  ];

  // Last visit
  $data['ip_tracker']['last_visit'] = [
    'title' => t('Last visit'),
    'help' => t('Last visit: registration/login.'),
    'field' => [
      'id' => 'date',
    ],
    'sort' => [
      'id' => 'date',
    ],
    'filter' => [
      'id' => 'date',
    ],
    'argument' => [
      'id' => 'date',
    ],
  ];

  // Last visit
  $data['ip_tracker']['user_count'] = [
    'title' => t('User count'),
    'help' => t('User count.'),
    'field' => [
      'id' => 'ip_views_handler_field_user_count',
      'click sortable' => FALSE,
    ],
  ];

  // Table: IP Posts:
  $data['ip_posts']['table']['group'] = t('IP Posts');
  $data['ip_posts']['table']['base'] = [
    'field' => 'type',
    'title' => t('IP Posts (entity) table.'),
    'help' => t('IP Posts (entity) table.'),
    'weight' => -10,
  ];

  // Type
  $data['ip_posts']['type'] = [
    'title' => t('Entity type'),
    'help' => t('Entity type'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'standard',
    ],
    'argument' => [
      'id' => 'standard',
    ],
  ];

  // Id field
  $data['ip_posts']['id'] = [
    'title' => t('Entity id'),
    'help' => t('Entity id'),
    'field' => [
      'id' => 'numeric',
    ],
    'sort' => [
      'id' => 'numeric',
    ],
    'filter' => [
      'id' => 'numeric',
    ],
    'argument' => [
      'id' => 'numeric',
    ],
  ];

  // IP field
  $data['ip_posts']['ip'] = [
    'title' => t('IP Address'),
    'help' => t('IP address of user.'),
    'field' => [
      'id' => 'long2ip',
    ],
    'sort' => [
      'id' => 'numeric',
    ],
    'filter' => [
      'id' => 'ip2long',
    ],
    'argument' => [
      'id' => 'ip2long',
    ],
  ];

  // Node relationship: ip_posts -> node_field_data.
  $data['ip_posts']['node_field_data'] = [
    'title' => t('Node'),
    'help' => t('Node'),
    'relationship' => [
      'base' => 'node_field_data',
      'base field' => 'nid',
      'field' => 'id',
      'id' => 'standard',
      'label' => t('Node'),
      'extra' => [
        0 => [
          'left_field' => 'type',
          'value' => 'node',
        ],
      ],
    ],
  ];

  // Node relationship: node_field_data -> ip_posts.
  $data['node_field_data']['ip_posts'] = [
    'title' => t('IP Posts'),
    'help' => t('IP Posts (node)'),
    'relationship' => [
      'base' => 'ip_posts',
      'base field' => 'id',
      'field' => 'nid',
      'id' => 'standard',
      'label' => t('IP Posts'),
      'extra' => [
        0 => [
          'field' => 'type',
          'value' => 'node',
        ],
      ],
    ],
  ];

  // Comment relationship: ip_posts -> comment_field_data.
  $data['ip_posts']['comment_field_data'] = [
    'title' => t('Comment'),
    'help' => t('Comment'),
    'relationship' => [
      'base' => 'comment_field_data',
      'base field' => 'cid',
      'field' => 'id',
      'id' => 'standard',
      'label' => t('Comment'),
      'extra' => [
        0 => [
          'left_field' => 'type',
          'value' => 'comment',
        ],
      ],
    ],
  ];

  // Comment relationship: comment_field_data -> ip_posts.
  $data['comment_field_data']['ip_posts'] = [
    'title' => t('IP Posts'),
    'help' => t('IP Posts (comment)'),
    'relationship' => [
      'base' => 'ip_posts',
      'base field' => 'id',
      'field' => 'cid',
      'id' => 'standard',
      'label' => t('IP Posts'),
      'extra' => [
        0 => [
          'field' => 'type',
          'value' => 'comment',
        ],
      ],
    ],
  ];

  return $data;
}
