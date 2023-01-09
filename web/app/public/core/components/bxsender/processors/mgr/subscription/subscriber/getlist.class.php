<?php
/**
 * Get a list of Subscribers
 */
class bxSubscriberGetListProcessor extends modObjectGetListProcessor
{
    public $objectType = 'bxSubscriber';
    public $classKey = 'bxSubscriber';
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'DESC';
    public $languageTopics = array('bxsender:manager', 'bxsender:subscription');


    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {

        $c->select($this->modx->getSelectColumns($this->classKey, $this->classKey));
        $c->leftJoin('bxSubscriberMember', 'Members');
        $c->select('COUNT(`Members`.`subscriber_id`) as `segments_count`');
        $c->groupby('bxSubscriber.id');


        if (!$this->setCheckbox('combo')) {

            $c->leftJoin('modUser', 'modUser', 'bxSubscriber.user_id = modUser.id');
            $c->select('modUser.username');

            if ($query = $this->getProperty('user_id', null)) {
                $c->where(array(
                    'user_id' => $query
                ));
            }

            $active = $this->getProperty('active');
            if ($active) {
                $c->where(array(
                    'active' => true
                ));
            }


            $segment = $this->getProperty('segment');
            if (!empty($segment)) {
                $c->innerJoin('bxSubscriberMember', 'Member', 'bxSubscriber.id = Member.subscriber_id');
                $c->where(array(
                    'Member.segment_id' => $segment
                ));
            }

        }

        if ($query = $this->getProperty('query', null)) {
            $c->where(array(
                'email:LIKE' => "%{$query}%",
                'OR:fullname:LIKE' => "%{$query}%",
            ));
        }
        return $c;
    }

    /** {@inheritDoc} */
    public function prepareRow(xPDOObject $object)
    {
        /* @var bxSubscriber $object */
        $array = $object->toArray();
        if (!$this->setCheckbox('combo')) {
            $array['actions'] = array();

            // Disable
            if (empty($array['active'])) {
                $array['actions'][] = array(
                    'class' => '',
                    'button' => true,
                    'menu' => true,

                    'multiple' => true,
                    'action' => 'enable',
                    'icon' => 'icon icon-check',
                    'title' => $this->modx->lexicon('bxsender_action_enable'),

                );
            } // or Enable
            else {
                $array['actions'][] = array(
                    'class' => '',
                    'button' => true,
                    'menu' => true,
                    'multiple' => true,
                    'action' => 'disable',
                    'icon' => 'icon icon-power-off',
                    'title' => $this->modx->lexicon('bxsender_action_disable'),
                );
            }

            // Remove
            $array['actions'][] = array(
                'class' => '',
                'button' => true,
                'menu' => true,
                'multiple' => true,
                'action' => 'remove',
                'icon' => 'icon icon-trash-o',
                'title' => $this->modx->lexicon('bxsender_action_remove'),
            );
        }
        return $array;
    }


}

return 'bxSubscriberGetListProcessor';