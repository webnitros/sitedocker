<?php
/**
 * Get a list of Items
 */
class modDevToolsChunkGetListProcessor extends modObjectGetListProcessor {
	public $objectType = 'modChunk';
	public $classKey = 'modChunk';
	public $defaultSortField = 'modChunk.name';
	public $defaultSortDirection = 'ASC';
	public $renderers = '';


	/**
	 * @param xPDOQuery $c
	 *
	 * @return xPDOQuery
	 */
	public function prepareQueryBeforeCount(xPDOQuery $c) {
        $c->leftJoin('modDevToolsLink','Link','modChunk.id=Link.child');
        $c->where(array(
            'Link.link_type' => $this->getProperty('link_type'),
            'Link.parent' => $this->getProperty('parent'),
        ));
		return $c;
	}

    /**
     * Handle virtual chunks
     * @param xPDOObject $object
     * @return array
     */
    public function prepareRow(xPDOObject $object) {
        $data = $object->toArray();
        $data['virtual'] = false;
        return $data;
    }

    public function afterIteration(array $list) {
        $path = $this->modx->getOption('moddevtools_core_path',null,$this->modx->getOption('core_path').'components/moddevtools/').'model/moddevtools/';
        /** @var modDevTools $devTools */
        $devTools = $this->modx->getService('devTools','modDevTools',$path);
        if (!$devTools->config['createVirtual']) {
            return $list;
        }

        $link = explode('-', $this->getProperty('link_type'));
        if ($link[0] == 'temp') {
            $obj = $this->modx->getObject('modTemplate', $this->getProperty('parent'));
        } else if ($link[0] == 'chunk') {
            $obj = $this->modx->getObject('modChunk', $this->getProperty('parent'));
        } else {
            return $list;
        }

        $devTools->findTags($obj->get('content'), $tags);
        foreach ($tags as $tag) {
            $child = $devTools->findObject('modChunk', $tag['name']);
            if (!$child) {
                /** @var modChunk $virt_chunk */
                $virt_chunk = $this->modx->newObject('modChunk');
                $virt_chunk->set('name', $tag['name']);
                if ($virt_chunk->validate()) {
                    $list[] = array(
                        'id' => 0,
                        'name' => $tag['name'],
                        'snippet' => '',
                        'virtual' => true,
                    );
                }
            }
        }

        return $list;
    }

}

return 'modDevToolsChunkGetListProcessor';
