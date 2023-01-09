<?php

/**
 * Get a list of Segments
 */
class bxSegmentGetListProcessor extends modObjectGetListProcessor
{
    public $objectType = 'bxSegment';
    public $classKey = 'bxSegment';
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'DESC';
    public $languageTopics = array('bxsender:manager', 'bxsender:subscriber');

    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c->leftJoin('bxMailingMember', 'Recipients',
            "Recipients.segment_id = {$this->classKey}.id AND Recipients.mailing_id = {$this->getProperty('mailing')}"
        );
        $c->select($this->modx->getSelectColumns($this->classKey, $this->classKey));
        $c->select('(Recipients.segment_id is not null) as active');


        $t_m = $this->modx->getTableName('bxSubscriberMember');
        $t_s = $this->modx->getTableName('bxSubscriber');
        $c->select("(SELECT COUNT(DISTINCT Subscriber.subscriber_id) FROM {$t_m} as Subscriber INNER JOIN {$t_s} AS Sub ON Sub.id = Subscriber.subscriber_id AND Sub.active = 1 WHERE Subscriber.segment_id = bxSegment.id) as subscriber_count");

        $c->groupby($this->classKey . '.id');

        $c->where(array(
            'bxSegment.active' => 1
        ));

        return $c;
    }


    /**
     * @param xPDOObject $object
     *
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $array = $object->toArray();
        if (empty($array['active'])) {
            $array['actions'][] = array(
                'cls' => '',
                'icon' => 'icon icon-power-off action-green',
                'title' => $this->modx->lexicon('bxsender_action_enable'),
                'multiple' => $this->modx->lexicon('bxsender_action_enable'),
                'action' => 'enableSegment',
                'button' => true,
                'menu' => true,
            );
        } else {
            $array['actions'][] = array(
                'cls' => '',
                'icon' => 'icon icon-power-off action-gray',
                'title' => $this->modx->lexicon('bxsender_action_disable'),
                'multiple' => $this->modx->lexicon('bxsender_action_disable'),
                'action' => 'disableSegment',
                'button' => true,
                'menu' => true,
            );
        }
        return $array;
    }

}

return 'bxSegmentGetListProcessor';