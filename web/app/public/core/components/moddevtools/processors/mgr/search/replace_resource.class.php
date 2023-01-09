<?php

include_once MODX_CORE_PATH . 'model/modx/processors/resource/update.class.php';
class modDevToolsResourceReplaceProcessor extends modResourceUpdateProcessor {

    public function beforeSet() {
        $props = $this->getProperties();

        $content = $this->object->getContent();

        if ($props['all']) {
            $content = str_replace($props['search'], $props['replace'], $content);
            $offset = 0;
        } else {
            $offset = (int)$this->getProperty('offset', 0);
            $offsetString = substr($content, 0, $offset);
            $newContent = substr($content, $offset);
            $strings = explode($props['search'], $newContent, 2);
            $newContent = implode($props['replace'], $strings);

            if (strpos($strings[1], $props['search']) === false) {
                $offset = 0;
            } else {
                $offset = $offset + strlen($strings[0]) + strlen($props['replace']);
            }
            $content = $offsetString . $newContent;
        }
        $this->setProperty('content', $content);
        $this->setProperty('pagetitle', $this->object->get('pagetitle'));
        $this->setProperty('offset', $offset);

        return !$this->hasErrors();
    }

    public function cleanup() {
        $object = array(
            'id' => $this->object->get('id'),
            'name' => $this->object->get('pagetitle'),
            'class' => $this->classKey,
            'content' => $this->modx->moddevtools->getSearchContent(
                $this->object->get('content'),
                $this->getProperty('search'),
                $this->getProperty('offset')),
            'offset' => $this->getProperty('offset'),
        );

        return $this->success('', $object);
    }

}

return 'modDevToolsResourceReplaceProcessor';