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

        $c->select($this->modx->getSelectColumns($this->classKey, $this->classKey));


        if (!$this->getProperty('combo', false)) {
            $c->leftJoin('bxSubscriberMember', 'Subscribers');
            $c->select('COUNT(`Subscribers`.`segment_id`) as `subscribers`');
            $c->groupby($this->classKey . '.id');
        } else {
            $c->where(array(
                'active' => 1
            ));
        }


        if ($query = $this->getProperty('query')) {
            $c->where(array(
                'name:LIKE' => "%$query%",
                'OR:description:LIKE' => "%$query%"
            ));
        }
        if ($this->getProperty('combo')) {
            $c->where(array('active' => 1));
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

        if (!$this->getProperty('combo', false)) {


            $array['actions'] = array();

            // Update
            $array['actions'][] = array(
                'class' => '',
                'button' => true,
                'menu' => true,
                'action' => 'update',
                'icon' => 'icon icon-edit',
                'title' => $this->modx->lexicon('bxsender_action_update'),
            );

            // Disable
            if (empty($array['active'])) {
                $array['actions'][] = array(
                    'class' => '',
                    'button' => false,
                    'menu' => true,

                    'action' => 'enable',
                    'icon' => 'icon icon-check',
                    'title' => $this->modx->lexicon('bxsender_action_enable'),
                );
            } // or Enable
            else {
                $array['actions'][] = array(
                    'class' => '',
                    'button' => false,
                    'menu' => true,


                    'action' => 'disable',
                    'icon' => 'icon icon-power-off',
                    'title' => $this->modx->lexicon('bxsender_action_disable'),
                );
            }

            // copy
            $array['actions'][] = array(
                'class' => '',
                'button' => false,
                'menu' => true,

                'action' => 'copy',
                'icon' => 'icon icon-copy',
                'title' => $this->modx->lexicon('bxsender_action_copy'),

            );

            // Remove
            $array['actions'][] = array(
                'class' => '',
                'button' => false,
                'menu' => true,
                'action' => 'remove',
                'icon' => 'icon icon-trash-o',
                'title' => $this->modx->lexicon('bxsender_action_remove'),
            );
        }
        return $array;
    }

}

return 'bxSegmentGetListProcessor';