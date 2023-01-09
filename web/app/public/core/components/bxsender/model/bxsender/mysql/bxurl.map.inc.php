<?php
$xpdo_meta_map['bxUrl']= array (
  'package' => 'bxsender',
  'version' => '1.1',
  'table' => 'bx_url',
  'extends' => 'xPDOSimpleObject',
  'tableMeta' => 
  array (
    'engine' => 'MyISAM',
  ),
  'fields' => 
  array (
    'queue_id' => 0,
    'mailing_id' => 0,
    'subscriber_id' => 0,
    'hash' => '',
    'url' => '',
  ),
  'fieldMeta' => 
  array (
    'queue_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'attributes' => 'unsigned',
      'null' => false,
      'default' => 0,
    ),
    'mailing_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'attributes' => 'unsigned',
      'null' => false,
      'default' => 0,
    ),
    'subscriber_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'attributes' => 'unsigned',
      'null' => true,
      'default' => 0,
    ),
    'hash' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '12',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
    'url' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '2000',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
  ),
  'aggregates' => 
  array (
    'Queue' => 
    array (
      'class' => 'bxQueue',
      'local' => 'queue_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
