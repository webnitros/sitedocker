<?php

/**
 * Create an Subscriber
 */
trait bxSubscriberTrait
{
    /* @var modX $modx */


    /**
     * Добавление подписки в сегменты
     * @param bxSubscriber $Subscriber
     * @param array $segments
     * @return bool
     */
    public function addSegment(bxSubscriber $Subscriber, $segments = array())
    {
        if (count($segments) > 0) {
            $newSegments = array();
            foreach ($segments as $segment) {
                $newSegments[$segment] = 1;
            }
            $segments = $newSegments;
            foreach ($segments as $segment_id => $checked) {
                $segment_id = (int)$segment_id;
                $isExists = false;
                if (!$Subscriber->isNew()) {
                    if ($checked) {
                        if ($count = (boolean)$this->modx->getCount('bxSubscriberMember', array(
                            'subscriber_id' => $Subscriber->get('id'),
                            'segment_id' => $segment_id
                        ))) {
                            $isExists = true;
                        }
                    }
                }
                if (!$isExists) {
                    /* @var bxSubscriberMember $object */
                    $segment = $this->modx->newObject('bxSubscriberMember');
                    $segment->set('segment_id', $segment_id);
                    if (!$Subscriber->addMany($segment, 'Members')) {
                        return false;
                    }
                }
            }
        }
        return true;
    }


    /**
     * @param $fullname
     * @return string
     */
    public function checkFullname($fullname)
    {
        $fullname = $this->modx->stripTags(trim($fullname));
        return $fullname;
    }

    /**
     * Массовое добавление подписчиков в сегмент
     * @param array $array
     * @param array $segments
     * @param bool $replace_fullname
     * @param bool $replace_user_id
     * @param bool $search_user
     * @return int
     */
    public function addSubscribers($array = array(), $segments = array(), $replace_fullname = false, $replace_user_id = false, $search_user = false)
    {
        $newSubscribeTotal = 0;
        if (count($array) > 0) {


            $newSegments = array();
            foreach ($segments as $segment) {
                $newSegments[$segment] = 1;
            }
            $segments = $newSegments;
            foreach ($array as $key => $row) {

                if (!isset($row['email'])) {
                    continue;
                }

                $email = trim($row['email']);

                $fullname = isset($row['fullname']) ? $this->checkFullname($row['fullname']) : '';
                $user_id = isset($row['user_id']) ? trim($row['user_id']) : '';

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $this->modx->log(modX::LOG_LEVEL_ERROR, "Import CSV:: invalid email address: " . $email, '', __METHOD__, __FILE__, __LINE__);
                    continue;
                }

                if (!empty($email)) {
                    $criteria = array(
                        'email' => $email,
                    );

                    /* @var bxSubscriber $Subscriber */
                    if (!$Subscriber = $this->modx->getObject('bxSubscriber', $criteria)) {

                        /* @var bxSubscriber $object */
                        $Subscriber = $this->modx->newObject('bxSubscriber');
                        $Subscriber->fromArray(array_merge($criteria, array(
                            'user_id' => $user_id,
                            'fullname' => $fullname,
                            'createdon' => time(),
                            'active' => 1,
                        )));
                    } else {

                        if ($replace_fullname) {
                            $Subscriber->set('fullname', $fullname);
                        }

                        if ($replace_user_id) {
                            $Subscriber->set('user_id', $user_id);
                        }


                    }


                    if ($search_user) {
                        // Поиск пользователя по e-mail адресу
                        $q = $this->modx->newQuery('modUserProfile');
                        $q->select('internalKey');
                        $q->where(array('email' => $email));
                        if ($q->prepare() && $q->stmt->execute()) {
                            $rows = $q->stmt->fetchAll(PDO::FETCH_ASSOC);
                            if (count($rows) > 0) {
                                $internalKey = $rows[0]['internalKey'];
                                $Subscriber->set('user_id', $internalKey);
                            }
                        }
                    }


                    $this->addSegment($Subscriber, $segments);

                    // Сохраняем емаил и подписку
                    $Subscriber->save();
                    $newSubscribeTotal++;
                }
            }

        }
        return $newSubscribeTotal;
    }
}