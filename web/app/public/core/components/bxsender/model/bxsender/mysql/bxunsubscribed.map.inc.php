<?php
$xpdo_meta_map['bxUnSubscribed']= array (
  'package' => 'bxsender',
  'version' => '1.1',
  'table' => 'bx_unsubscribed',
  'extends' => 'xPDOSimpleObject',
  'tableMeta' => 
  array (
    'engine' => 'MyISAM',
  ),
  'fields' => 
  array (
    'email' => '',
    'createdon' => 0,
  ),
  'fieldMeta' => 
  array (
    'email' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'createdon' => 
    array (
      'dbtype' => 'int',
      'precision' => '20',
      'phptype' => 'timestamp',
      'null' => false,
      'default' => 0,
    ),
  ),
  'indexes' => 
  array (
    'email' => 
    array (
      'alias' => 'email',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'email' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
  'aggregates' => 
  array (
    'Subscriber' => 
    array (
      'class' => 'bxSubscriber',
      'local' => 'email',
      'foreign' => 'email',
      'owner' => 'foreign',
      'cardinality' => 'one',
    ),
  ),
);
