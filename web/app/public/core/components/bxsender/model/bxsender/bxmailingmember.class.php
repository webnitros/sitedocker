<?php

class bxMailingMember extends xPDOObject
{

    /* @var bxSegment $segment */
    protected $segment = null;

    /**
     * Loads Segment
     * @return null|bxSegment
     */
    public function loadSegment($false = false)
    {
        if (!is_object($this->segment) || !($this->segment instanceof bxSegment)) {
            if (!$this->segment = $this->getOne('Segment')) {
                $this->segment = $false ? false : $this->xpdo->newObject('bxSegment');
            }
        }
        return $this->segment;
    }


    /* @var bxMailing $mailing */
    protected $mailing = null;

    /**
     * Loads ailing
     * @return null|bxMailing
     */
    public function loadMailing($false = false)
    {
        if (!is_object($this->mailing) || !($this->mailing instanceof bxMailing)) {
            if (!$this->mailing = $this->getOne('Mailing')) {
                $this->mailing = $false ? false : $this->xpdo->newObject('bxMailing');
            }
        }
        return $this->mailing;
    }


    /* @var bxSubscriber[] $subscribers */
    protected $subscribers = null;

    /**
     * Loads Subscribers
     * @return bool|bxSubscriber[]
     */
    public function loadSubscribers()
    {
        if (!is_array($this->subscribers) and !is_bool($this->subscribers)) {
            if (!$this->subscribers = $this->getMany('Subscribers')) {
                $this->subscribers = false;
            }
        }
        return $this->subscribers;
    }


}