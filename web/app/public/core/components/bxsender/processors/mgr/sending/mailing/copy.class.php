<?php

/**
 * Disable an Mailing
 */
class bxMailingCopyProcessor extends modProcessor
{
    public $objectType = 'bxMailing';
    public $classKey = 'bxMailing';
    public $languageTopics = array('bxsender');


    /** {inheritDoc} */
    public function process()
    {
        if (!$ids = explode(',', $this->getProperty('ids'))) {
            return $this->failure($this->modx->lexicon('bxsender_mailing_err_ns'));
        }


        $mailings = $this->modx->getIterator($this->classKey, array('id:IN' => $ids));
        /** @var bxMailing $mailing */
        foreach ($mailings as $mailing) {

            /** @var bxMailing $newMailing */
            $newMailing = $this->modx->newObject($this->classKey);

            $newMailing->fromArray($mailing->get(
                array(
                    'message',
                    'visual_editor',
                    'utm',
                    'utm_source',
                    'utm_medium',
                    'utm_campaign',
                    'description',
                    'properties',
                    'service',
                )
            ));


            $name = $mailing->get('subject') . '_copy';
            $count = $this->modx->getCount($this->classKey, array('name' => $name));
            if ($count > 0) {
                $name .= $count;
            }

            $segment_ids = null;
            if ($SegmentMembers = $mailing->loadSegmentMember()) {
                foreach ($SegmentMembers as $segmentMember) {
                    $segment_ids[] = $segmentMember->get('segment_id');
                    /* @var bxMailingMember $object */
                    $Member = $this->modx->newObject('bxMailingMember');
                    $Member->set('segment_id', $segmentMember->get('segment_id'));
                    $newMailing->addMany($Member);
                }
            }

            $subscribers_count = 0;
            if ($segment_ids) {
                $subscribers_count = $newMailing->getCountSubscribers($segment_ids);
            }

            $newMailing->set('subscribers_count', $subscribers_count);
            $newMailing->set('shipping_status', 'draft');
            $newMailing->set('subject', $name);
            $newMailing->save();
        }

        return $this->success();
    }

}

return 'bxMailingCopyProcessor';
