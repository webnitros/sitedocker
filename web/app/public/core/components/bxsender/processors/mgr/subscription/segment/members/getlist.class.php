<?php

/**
 * Get a list of Segments
 */
class bxSubscriberGetListProcessor extends modObjectGetListProcessor
{
    public $objectType = 'bxSubscriber';
    public $classKey = 'bxSubscriber';
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

        $this->modx->log(modX::LOG_LEVEL_ERROR, "segment_id 222", '', __METHOD__, __FILE__, __LINE__);

        $segment = $this->getProperty('segment');
        $c->leftJoin('bxSubscriberMember', 'Members', "Members.subscriber_id = {$this->classKey}.id AND Members.segment_id = {$segment}");
        $c->select($this->modx->getSelectColumns($this->classKey, $this->classKey, '', array('id', 'email', 'fullname', 'user_id')));
        $c->select('(Members.subscriber_id is not null) as active');
        $c->where(array(
            'Members.segment_id' => $segment
        ));


        if ($query = $this->getProperty('query', null)) {
            $c->where(array(
                'bxSubscriber.email:LIKE' => "%{$query}%",
            ));
        }

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
                'action' => 'enableSubscriber',
                'button' => true,
                'menu' => true,
            );
        } else {
            $array['actions'][] = array(
                'cls' => '',
                'icon' => 'icon icon-power-off action-gray',
                'title' => $this->modx->lexicon('bxsender_action_disable'),
                'multiple' => $this->modx->lexicon('bxsender_action_disable'),
                'action' => 'disableSubscriber',
                'button' => true,
                'menu' => true,
            );
        }
        return $array;
    }

}

return 'bxSubscriberGetListProcessor';