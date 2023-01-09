<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 31.05.2019
 * Time: 20:48
 */

abstract class bxSenderControllerDefault
{
    /* @var bxSender $bx */
    public $bx;

    /* @var modX $modx */
    public $modx;

    /* @var array $errors */
    protected $errors = array();
    public $config = array();

    /**
     * @param bxSender $bx
     */
    function __construct(bxSender &$bx, array $config = array())
    {
        $this->bx = $bx;
        $this->modx = $bx->modx;

        $this->config = array_merge(array(
            'form' => 'tpl.bxSubscribeRestore',
            'action' => 'restore',
            'captchaEnable' => false,
        ), $config);


        $this->setDefault($config);
        $this->bx->loadPdoTools();
        $this->bx->loadLexicon('frontend');
    }


    /**
     * @return bool
     */
    public function initialize()
    {
        return true;
    }

    /**
     * @param array $config
     */
    public function setDefault($config = array())
    {
        $this->config = $config;
    }

    /**
     * @param array $data
     * @return array|string
     */
    public function hook(array $data = array())
    {
        return $this->success();
    }

    public function regJsScript()
    {
        if ($this->config['mode'] == 'api') {
            $this->modx->regClientScript(MODX_ASSETS_URL . 'components/ajaxform/js/lib/jquery.min.js');
        }
    }
    public function regJsScriptAfter()
    {

    }

    /**
     * Вернет ajaxForm
     * @param array $data
     * @return string
     */
    public function getAjaxForm(array $data = array())
    {

        // Регистрация данных в форме
        if (count($data) > 0) {
            foreach ($data as $filed => $value) {
                $this->modx->setPlaceholder('fi.' . $filed, $value);
            }
        }

        $this->regJsScript();

        /** @var modSnippet $snippet */
        if ($snippet = $this->modx->getObject('modSnippet', array('name' => 'AjaxForm'))) {
            $snippet->_cacheable = false;
            $snippet->_processed = false;

            $this->config['form'] = $this->config['tplForm'];
            $outer = $snippet->process(array_merge(array('snippet' => 'bxSubscribeHook'), $this->config));

            $this->regJsScriptAfter();
            return $this->renderAPI($outer);
        }

        return 'Could not found snippet AjaxForm';
    }

    public function getParser($outer = '')
    {
        $this->modx->getParser()->processElementTags('', $outer, true, false, '[[', ']]', array(), 10);
        $this->modx->getParser()->processElementTags('', $outer, true, true, '[[', ']]', array(), 10);
        return $outer;
    }

    public function renderAPI($outer = '')
    {
        if ($this->config['mode'] == 'api') {
            $outer = $this->getParser($outer);
            $outer = $this->regJs($outer);
            $outer = $this->regCss($outer);
        }

        return $outer;
    }

    /**
     * @param string $outer
     * @return mixed|string
     */
    public function regJs($outer = '')
    {
        $js = $this->modx->getRegisteredClientScripts();
        if (!empty($outer) and !empty($js)) {
            $outer = preg_replace("/(<\/form>)/i", $js . "\n\\1", $outer, 1);
        }

        return $outer;
    }

    /**
     * @param string $outer
     * @return mixed|string
     */
    public function regCss($outer = '')
    {
        $js = $this->modx->getRegisteredClientStartupScripts();
        if (!empty($outer) and !empty($js)) {
            $outer = preg_replace("/(<\/form>)/i", $js . "\n\\1", $outer, 1);
        }
        return $outer;
    }


    /** @inheritdoc} */
    public function vFullname($fullname)
    {
        $fullname = trim($fullname);
        if (empty($fullname)) {
            return false;
        }
        return !preg_match("/[^a-zа-яё ]/iu", $fullname);
    }

    /**
     * Confirms email of user
     *
     * @param $hash
     *
     * @return bool
     */
    public function confirmEmail($hash, $remove_read = true, $topic = 'subscribe', $forcedRemoval = false)
    {
        if (empty($hash)) {
            return false;
        }

        /** @var modRegistry $registry */
        $registry = $this->modx->getService('registry', 'registry.modRegistry');
        $instance = $registry->getRegister('user', 'registry.modDbRegister');
        $instance->connect();
        $instance->subscribe('/bxsender/' . $topic . '/' . $hash);
        $response = $instance->read(array('poll_limit' => 1, 'remove_read' => $remove_read));

        if ($forcedRemoval) {
            return true;
        }
        if (!$remove_read) {
            return !empty($response[0]);
        }

        switch ($topic) {
            case 'subscribe':
                if (!empty($response[0])) {
                    $response = reset($response);
                    if (isset($response['email'])) {
                        /* @var bxSubscriber $Subscriber */
                        if ($Subscriber = $this->modx->getObject('bxSubscriber', array('email' => $response['email']))) {
                            return $Subscriber->addSubscribed(true);
                        }
                    }
                }
                break;
            case 'restore':
                if (!empty($response[0])) {
                    return $response;
                }
                break;
            default:
                break;
        }

        return false;
    }

    /**
     * Хеш для активации e-mail адреса
     * @return bool|string
     */
    public function activationEmailHash($email, $topic = 'subscribe', $hash = false)
    {
        if (empty($email)) {
            return false;
        }
        $hash = $hash ? $hash : sha1(uniqid(sha1($email), true));

        /** @var modRegistry $registry */
        $registry = $this->modx->getService('registry', 'registry.modRegistry');
        $instance = $registry->getRegister('user', 'registry.modDbRegister');
        $instance->connect();
        $instance->subscribe('/bxsender/' . $topic . '/');
        $instance->send('/bxsender/' . $topic . '/',
            array(
                $hash => array(
                    'email' => $email,
                )
            ),
            array(
                'ttl' => $this->bx->config['linkTTL']
            )
        );
        return $hash;
    }


    /**
     * @return bool
     */
    public function isError()
    {
        return count($this->errors) > 0;
    }

    /**
     * @param $field
     * @param $text
     */
    public function setError($field, $text)
    {
        $str = $this->modx->lexicon($text);
        if ($str != $text) {
            $text = $str;
        }
        $this->errors[$field] = $text;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * This method returns an error response
     *
     * @param string $message A lexicon key for error message
     * @param array $data Additional data, for example cart status
     * @param array $placeholders Array with placeholders for lexicon entry
     *
     * @return array|string $response
     * */
    public function error($message = '', $data = array(), $placeholders = array())
    {
        $response = array(
            'success' => false,
            'message' => $this->modx->lexicon($message, $placeholders),
            'data' => $data,
        );

        return $this->config['json_response']
            ? json_encode($response)
            : $response;
    }


    /**
     * This method returns an success response
     *
     * @param string $message A lexicon key for success message
     * @param array $data Additional data, for example cart status
     * @param array $placeholders Array with placeholders for lexicon entry
     *
     * @return array|string $response
     * */
    public function success($message = '', $data = array(), $placeholders = array())
    {
        $response = array(
            'success' => true,
            'message' => $this->modx->lexicon($message, $placeholders),
            'data' => $data,
        );

        return $this->config['json_response']
            ? json_encode($response)
            : $response;
    }


    /**
     * Отправка сообщения
     * @param $email
     * @param $tpl
     * @param array $data
     * @return bool|string
     */
    public function sendEmail($email, $tpl, $data = array())
    {
        $email_subject = $this->bx->loadPdoTools()->getChunk($tpl, array_merge($data, array('is_email_subject' => true, 'is_email_body' => false)));
        $email_body = $this->bx->loadPdoTools()->getChunk($tpl, array_merge($data, array('is_email_subject' => false, 'is_email_body' => true)));
        return $response = $this->bx->sendEmail($email, array(
            'email_subject' => $email_subject,
            'email_body' => $email_body,
        ));
    }
}