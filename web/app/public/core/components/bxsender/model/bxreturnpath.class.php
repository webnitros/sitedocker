<?php

class bxReturnPath
{

    /* @var modX $modx */
    public $modx;

    /* @var string|null $path */
    protected $path = null;


    /* @var array|null $settings */
    protected $settings = null;

    public function __construct(modX $modx)
    {
        $this->modx = $modx;
        $this->loadSettings();
    }

    /**
     * @return array
     */
    private function loadSettings()
    {
        if (is_null($this->settings)) {
            $this->settings = array(
                'enable' => false,
                'email' => '',
                'host' => '',
                'username' => '',
                'password' => '',
                'port' => '',
                'ssl' => false,
                'timeout' => '',
            );
            foreach ($this->settings as $key => $value) {
                $value = $this->modx->getOption('bxsender_returnpath_' . $key);
                $this->settings[$key] = $value;
            }
        }
        return $this->settings;
    }


    /**
     * Если email отправителя отличается от емаил обраного пути то обратный путь не добавляется
     * @param $email
     * @return bool
     */
    public function isEnableEMail($email)
    {
        if ($this->get('email') != $email) {
            return false;
        }
        return true;
    }


    /**
     * @return bool
     */
    public function isEnable()
    {
        return (boolean)$this->get('enable');
    }

    /**
     * @return array|null
     */
    public function toArray()
    {
        return $this->settings;
    }

    public function set($field, $value)
    {
        $this->settings[$field] = $value;
    }


    /**
     * @param $field
     * @return array|null|string
     */
    public function get($field)
    {
        $value = null;
        if (is_array($field)) {
            foreach ($field as $k) {
                if (isset($this->settings[$k])) {
                    $value[$k] = $this->settings[$k];
                }
            }
        } else {
            if (isset($this->settings[$field])) {
                $value = $this->settings[$field];
            }
        }
        return $value;
    }


    /**
     * @param array $data
     */
    public function fromArray($data = array())
    {
        foreach ($this->settings as $key => $setting) {
            if (isset($data[$key])) {
                $this->settings[$key] = $data[$key];
            }
        }
    }

    /**
     * Сохраненеи настроек
     */
    public function save()
    {
        foreach ($this->settings as $key => $value) {
            $key_option = 'bxsender_returnpath_' . $key;
            if ($SystemSetting = $this->modx->getObject('modSystemSetting', $key_option)) {
                $value = is_null($value) ? '' : $value;
                $SystemSetting->set('value', $value);
                $SystemSetting->set('editedon', time());
                $SystemSetting->save();
            }
        }

        // Сброс кэша
        $this->modx->getCacheManager();
        $this->modx->cacheManager->refresh(array(
            'system_settings' => array()
        ));
    }


    /**
     * Вернет путь для сохранения писем. Если папка отсутствует то автоматическе создает её
     * @return string
     */
    public function getPatch()
    {
        if (is_null($this->path)) {
            $this->path = dirname(dirname(dirname(dirname(__FILE__)))) . '/eml/return_path/';
            if (!file_exists($this->path)) {
                $cache = $this->modx->getCacheManager();
                $cache->writeTree($this->path);
            }
        }
        return $this->path;
    }

    /**
     * Вернет путь для сохранения писем. Если папка отсутствует то автоматическе создает её
     * @return string
     */
    public function getName($token, $eml = '.eml')
    {
        return $this->getPatch() . $token . $eml;
    }

    /**
     * Запуск процедуры получение данные с удаленного сервера
     * @return boolean|string
     */
    public function getting()
    {
        $data = array();

        /* @var modProcessorResponse $response */
        $response = $this->modx->runProcessor('settings/returnpath/undeliverable/getting', $data, array(
            'processors_path' => MODX_CORE_PATH . 'components/bxsender/processors/mgr/'
        ));
        if ($response->isError()) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, "Error " . print_r($response->getAllErrors(), 1), '', __METHOD__, __FILE__, __LINE__);
            return $response->getMessage();
        }
        return true;
    }

    /**
     * Чтение полученного сообщения
     * @return array|string
     */
    public function reading()
    {
        $data = array();

        /* @var modProcessorResponse $response */
        $response = $this->modx->runProcessor('settings/returnpath/undeliverable/reading', $data, array(
            'processors_path' => MODX_CORE_PATH . 'components/bxsender/processors/mgr/'
        ));
        if ($response->isError()) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, "Error " . print_r($response->getAllErrors(), 1), '', __METHOD__, __FILE__, __LINE__);
            return $response->getMessage();
        }

        return true;
    }


    /**
     * Устанавливаем метку что файл была прочитан
     * @param $token
     */
    public function read($token)
    {
        // Вырезаем расширение чтобы больше не читать это сообщение
        $newtoken = substr($token, 0, -4);
        $newtoken = $this->getPatch() . $newtoken;
        if (!file_exists($newtoken)) {
            $fp = fopen($newtoken, "w");
            // Сохраняем в файл только время прочтения сообщения
            fwrite($fp, time());
            fclose($fp);
        }

        // старый файл удаляем
        $path = $this->getPatch() . $token;
        if (file_exists($path)) {
            unlink($path);
        }
    }

    /**
     * Найдет письмо без разширения
     * @param $token
     * @return bool
     */
    public function findToken($token)
    {
        $path = $this->getPatch() . $token;
        if (file_exists($path)) {
            return true;
        }
        return false;
    }


}