<?php
$xpdo_meta_map['bxSubscriberMember']= array (
  'package' => 'bxsender',
  'version' => '1.1',
  'table' => 'bx_subscriber_segment',
  'extends' => 'xPDOObject',
  'tableMeta' => 
  array (
    'engine' => 'MyISAM',
  ),
  'fields' => 
  array (
    'subscriber_id' => NULL,
    'segment_id' => NULL,
  ),
  'fieldMeta' => 
  array (
    'subscriber_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'index' => 'pk',
    ),
    'segment_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'index' => 'pk',
    ),
  ),
  'indexes' => 
  array (
    'segment' => 
    array (
      'alias' => 'subscriber',
      'primary' => true,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'subscriber_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'segment_id' => 
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
      'local' => 'subscriber_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Segment' => 
    array (
      'class' => 'bxSegment',
      'local' => 'segment_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
