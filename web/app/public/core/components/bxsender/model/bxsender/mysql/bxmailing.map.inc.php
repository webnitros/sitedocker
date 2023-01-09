<?php
$xpdo_meta_map['bxMailing']= array (
  'package' => 'bxsender',
  'version' => '1.1',
  'table' => 'bx_mailing',
  'extends' => 'xPDOSimpleObject',
  'tableMeta' => 
  array (
    'engine' => 'MyISAM',
  ),
  'fields' => 
  array (
    'subject' => '',
    'message' => NULL,
    'visual_editor' => 0,
    'shipping_status' => 'draft',
    'utm' => 0,
    'utm_source' => 'bx_segment',
    'utm_medium' => 'bx_medium',
    'utm_campaign' => 'bx_mailing',
    'description' => NULL,
    'start_by_time' => 0,
    'start_by_timedon' => 0,
    'start_mailing' => 0,
    'end_mailing' => 0,
    'paused_mailing' => 0,
    'createdon' => 0,
    'updatedon' => 0,
    'start' => 0,
    'completed' => 0,
    'queue_created' => 0,
    'queue_preapre' => 0,
    'properties' => '',
    'active' => 1,
    'service' => 'bxsender',
  ),
  'fieldMeta' => 
  array (
    'subject' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
    'message' => 
    array (
      'dbtype' => 'mediumtext',
      'phptype' => 'string',
    ),
    'visual_editor' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'boolean',
      'attributes' => 'unsigned',
      'null' => true,
      'default' => 0,
    ),
    'shipping_status' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '30',
      'phptype' => 'string',
      'null' => true,
      'default' => 'draft',
    ),
    'utm' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'boolean',
      'attributes' => 'unsigned',
      'null' => true,
      'default' => 0,
    ),
    'utm_source' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
      'default' => 'bx_segment',
    ),
    'utm_medium' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
      'default' => 'bx_medium',
    ),
    'utm_campaign' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
      'default' => 'bx_mailing',
    ),
    'description' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
    'start_by_time' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'boolean',
      'attributes' => 'unsigned',
      'null' => true,
      'default' => 0,
    ),
    'start_by_timedon' => 
    array (
      'dbtype' => 'int',
      'precision' => '20',
      'phptype' => 'timestamp',
      'null' => false,
      'default' => 0,
    ),
    'start_mailing' => 
    array (
      'dbtype' => 'int',
      'precision' => '20',
      'phptype' => 'timestamp',
      'null' => false,
      'default' => 0,
    ),
    'end_mailing' => 
    array (
      'dbtype' => 'int',
      'precision' => '20',
      'phptype' => 'timestamp',
      'null' => false,
      'default' => 0,
    ),
    'paused_mailing' => 
    array (
      'dbtype' => 'int',
      'precision' => '20',
      'phptype' => 'timestamp',
      'null' => false,
      'default' => 0,
    ),
    'createdon' => 
    array (
      'dbtype' => 'int',
      'precision' => '20',
      'phptype' => 'timestamp',
      'null' => false,
      'default' => 0,
    ),
    'updatedon' => 
    array (
      'dbtype' => 'int',
      'precision' => '20',
      'phptype' => 'timestamp',
      'null' => false,
      'default' => 0,
    ),
    'start' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'boolean',
      'attributes' => 'unsigned',
      'null' => true,
      'default' => 0,
    ),
    'completed' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'boolean',
      'attributes' => 'unsigned',
      'null' => true,
      'default' => 0,
    ),
    'queue_created' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'boolean',
      'attributes' => 'unsigned',
      'null' => true,
      'default' => 0,
    ),
    'queue_preapre' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'boolean',
      'attributes' => 'unsigned',
      'null' => true,
      'default' => 0,
    ),
    'properties' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'json',
      'null' => false,
      'default' => '',
    ),
    'active' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'boolean',
      'attributes' => 'unsigned',
      'null' => true,
      'default' => 1,
    ),
    'service' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => true,
      'default' => 'bxsender',
    ),
  ),
  'indexes' => 
  array (
    'start_by_time' => 
    array (
      'alias' => 'start_by_time',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'start_by_time' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'subject' => 
    array (
      'alias' => 'subject',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'subject' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'start_mailing' => 
    array (
      'alias' => 'start_mailing',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'start_mailing' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'shipping_status' => 
    array (
      'alias' => 'shipping_status',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'shipping_status' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'active' => 
    array (
      'alias' => 'active',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'active' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'service' => 
    array (
      'alias' => 'service',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'service' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'queue_created' => 
    array (
      'alias' => 'queue_created',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'queue_created' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
  'composites' => 
  array (
    'Subscribers' => 
    array (
      'class' => 'bxSubscriber',
      'local' => 'id',
      'foreign' => 'segment_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'Members' => 
    array (
      'class' => 'bxMailingMember',
      'local' => 'id',
      'foreign' => 'mailing_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'Queues' => 
    array (
      'class' => 'bxQueue',
      'local' => 'id',
      'foreign' => 'mailing_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
  'aggregates' => 
  array (
    'ReturnPath' => 
    array (
      'class' => 'bxReturnPath',
      'local' => 'rp_id',
      'foreign' => 'id',
      'owner' => 'foreign',
      'cardinality' => 'one',
    ),
  ),
  'validation' => 
  array (
    'rules' => 
    array (
      'subject' => 
      array (
        'preventBlankSubject' => 
        array (
          'type' => 'xPDOValidationRule',
          'rule' => 'xPDOMinLengthValidationRule',
          'value' => '1',
          'message' => 'bxsender_validator_err_subject',
        ),
      ),
      'message' => 
      array (
        'preventBlankMessage' => 
        array (
          'type' => 'xPDOValidationRule',
          'rule' => 'xPDOMinLengthValidationRule',
          'value' => '1',
          'message' => 'bxsender_validator_err_message',
        ),
      ),
    ),
  ),
);
