<?php
$xpdo_meta_map['bxQueue']= array (
  'package' => 'bxsender',
  'version' => '1.1',
  'table' => 'bx_queue',
  'extends' => 'xPDOSimpleObject',
  'tableMeta' => 
  array (
    'engine' => 'MyISAM',
  ),
  'fields' => 
  array (
    'mailing_id' => 0,
    'subscriber_id' => 0,
    'action' => '',
    'processed' => 0,
    'state' => '',
    'reject_reason' => '',
    'createdon' => 0,
    'updatedon' => 0,
    'processed_date_open' => 0,
    'datesent' => 0,
    'user_id' => 0,
    'service' => 'bxsender',
    'service_queue_id' => 0,
    'email_to' => '',
    'email_subject' => '',
    'email_body' => NULL,
    'email_body_text' => NULL,
    'variables' => '',
    'service_message' => '',
    'failure' => 0,
    'delete_after_sending' => 0,
    'completed' => 0,
    'testing' => 0,
  ),
  'fieldMeta' => 
  array (
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
    'action' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '30',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'processed' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'boolean',
      'null' => false,
      'default' => 0,
    ),
    'state' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '30',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'reject_reason' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
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
    'updatedon' => 
    array (
      'dbtype' => 'int',
      'precision' => '20',
      'phptype' => 'timestamp',
      'null' => false,
      'default' => 0,
    ),
    'processed_date_open' => 
    array (
      'dbtype' => 'int',
      'precision' => '20',
      'phptype' => 'timestamp',
      'null' => false,
      'default' => 0,
    ),
    'datesent' => 
    array (
      'dbtype' => 'int',
      'precision' => '20',
      'phptype' => 'timestamp',
      'null' => false,
      'default' => 0,
    ),
    'user_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => true,
      'default' => 0,
    ),
    'service' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
      'default' => 'bxsender',
    ),
    'service_queue_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'attributes' => 'unsigned',
      'null' => true,
      'default' => 0,
    ),
    'email_to' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
    'email_subject' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
    'email_body' => 
    array (
      'dbtype' => 'mediumtext',
      'phptype' => 'string',
    ),
    'email_body_text' => 
    array (
      'dbtype' => 'mediumtext',
      'phptype' => 'string',
    ),
    'variables' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'json',
      'null' => true,
      'default' => '',
    ),
    'service_message' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'json',
      'null' => true,
      'default' => '',
    ),
    'failure' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'boolean',
      'null' => false,
      'default' => 0,
    ),
    'delete_after_sending' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'boolean',
      'null' => false,
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
    'testing' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'boolean',
      'attributes' => 'unsigned',
      'null' => true,
      'default' => 0,
    ),
  ),
  'indexes' => 
  array (
    'action' => 
    array (
      'alias' => 'action',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'action' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'state' => 
    array (
      'alias' => 'state',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'state' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'subscriber_id' => 
    array (
      'alias' => 'subscriber_id',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'subscriber_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'completed' => 
    array (
      'alias' => 'completed',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'completed' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'failure' => 
    array (
      'alias' => 'failure',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'failure' => 
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
    'testing' => 
    array (
      'alias' => 'testing',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'testing' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'processed_date_open' => 
    array (
      'alias' => 'processed_date_open',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'processed_date_open' => 
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
    'Log' => 
    array (
      'class' => 'bxQueueLog',
      'local' => 'id',
      'foreign' => 'message_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'Url' => 
    array (
      'class' => 'bxUrl',
      'local' => 'id',
      'foreign' => 'queue_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'StatUnDeliverable' => 
    array (
      'class' => 'bxStatUnDeliverable',
      'local' => 'id',
      'foreign' => 'queue_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'StatUnSubscribed' => 
    array (
      'class' => 'bxStatUnSubscribed',
      'local' => 'id',
      'foreign' => 'queue_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'StatOpens' => 
    array (
      'class' => 'bxStatOpens',
      'local' => 'id',
      'foreign' => 'queue_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'StatClicks' => 
    array (
      'class' => 'bxStatClicks',
      'local' => 'id',
      'foreign' => 'queue_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'OrderLog' => 
    array (
      'class' => 'bxOrderLog',
      'local' => 'id',
      'foreign' => 'queue_id',
      'cardinality' => 'many',
      'owner' => 'local',
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
    'Subscriber' => 
    array (
      'class' => 'bxSubscriber',
      'local' => 'subscriber_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'UnDeliverable' => 
    array (
      'class' => 'bxUnDeliverable',
      'local' => 'id',
      'foreign' => 'queue_id',
      'cardinality' => 'one',
      'owner' => 'local',
    ),
    'UnDeliverableEmail' => 
    array (
      'class' => 'bxUnDeliverable',
      'local' => 'email_to',
      'foreign' => 'email',
      'cardinality' => 'one',
      'owner' => 'local',
    ),
  ),
  'validation' => 
  array (
    'rules' => 
    array (
      'mailing_id' => 
      array (
        'preventBlankEvent' => 
        array (
          'type' => 'xPDOValidationRule',
          'rule' => 'xPDOForeignKeyConstraint',
          'foreign' => 'id',
          'local' => 'mailing_id',
          'alias' => 'Mailing',
          'class' => 'bxMailing',
          'message' => 'bxsender_subscriber_err_mailing',
        ),
      ),
    ),
  ),
);
