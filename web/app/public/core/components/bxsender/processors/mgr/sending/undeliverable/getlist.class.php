<?php
/**
 * Get a list of Subscribers
 */
class bxUnDeliverableGetListProcessor extends modObjectGetListProcessor
{
    public $objectType = 'bxUnDeliverable';
    public $classKey = 'bxUnDeliverable';
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'DESC';
    public $languageTopics = array('bxsender:manager','bxsender:subscription');

    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c->select($this->modx->getSelectColumns($this->classKey, $this->classKey));
        $query = trim($this->getProperty('query', null));
        if (!empty($query)) {
            $c->where(array(
                'email:LIKE' => "%{$query}%",
            ));
        }

        return $c;
    }


    /** {@inheritDoc} */
    public function prepareRow(xPDOObject $object)
    {
        $array = $object->toArray();

        $array['actions'] = array();
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
        return $array;
    }
}

return 'bxUnDeliverableGetListProcessor';