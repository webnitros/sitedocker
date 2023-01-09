<?php
$xpdo_meta_map['bxMailingMember']= array (
  'package' => 'bxsender',
  'version' => '1.1',
  'table' => 'bx_mailing_recipients',
  'extends' => 'xPDOObject',
  'tableMeta' => 
  array (
    'engine' => 'MyISAM',
  ),
  'fields' => 
  array (
    'mailing_id' => NULL,
    'segment_id' => NULL,
  ),
  'fieldMeta' => 
  array (
    'mailing_id' => 
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
    'recipient' => 
    array (
      'alias' => 'recipient',
      'primary' => true,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'mailing_id' => 
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
    'Mailing' => 
    array (
      'class' => 'bxMailing',
      'local' => 'mailing_id',
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
    'Subscribers' => 
    array (
      'class' => 'bxSubscriberMember',
      'local' => 'segment_id',
      'foreign' => 'segment_id',
      'cardinality' => 'many',
      'owner' => 'foreign',
    ),
  ),
);
