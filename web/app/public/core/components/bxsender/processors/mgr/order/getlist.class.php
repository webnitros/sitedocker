<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

class bxOrderLogListProcessor extends modObjectGetListProcessor
{
    public $objectType = 'bxOrderLog';
    public $classKey = 'bxOrderLog';
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'DESC';
    public $languageTopics = ['bxsender:manager'];
    //public $permission = 'list';


    /**
     * We do a special check of permissions
     * because our objects is not an instances of modAccessibleObject
     *
     * @return boolean|string
     */
    public function beforeQuery()
    {
        if (!$this->checkPermissions()) {
            return $this->modx->lexicon('access_denied');
        }

        return true;
    }


    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $order_id = (int)trim($this->getProperty('order_id'));
        if (!empty($order_id)) {
            $c->where([
                'order_id' => $order_id,
            ]);
        }


        #$c->leftJoin('bxQueue', 'Queue', 'Queue.id = bxOrderLog.queue_id');


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
        $status_name = '';

        /* @var msOrderStatus $OrderStatus */
        if ($OrderStatus = $this->modx->getObject('msOrderStatus', $array['status'])) {
            $color = $OrderStatus->get('color');
            $status_name = '<span style="color: #' . $color . '">' . $OrderStatus->get('name') . '</span>';
        }

        $array['createdon'] = strtotime($array['createdon']);
        $array['status_name'] = $status_name;

        $state = 'remove';
        if ($Queue = $object->getOne('Queue')) {
            $state = $Queue->get('state');
        }
        $array['state'] = $state;

        // showMessage
        $array['actions'][] = array(
            'class' => '',
            'button' => true,
            'menu' => true,
            'action' => 'showMessage',
            'icon' => 'icon icon-info',
            'title' => 'Показать сообщение',
        );

        return $array;
    }

}

return 'bxOrderLogListProcessor';