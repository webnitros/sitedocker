<?php

use Hybridauth\Adapter\OAuth2 as OAuth2;

class crossManagerHybridauth
{
    /** @var modX $this ->modx */
    public $modx;

    /** @var array() $config */
    public $config = array();

    /** @var array $initialized */
    public $initialized = array();


    /** @var array $adapters */
    public $adapters = [];


    /**
     * @param modX $this ->modx
     * @param array $config
     */
    function __construct(modX &$modx, array $config = [])
    {
        $this->modx =& $modx;
        $corePath = MODX_CORE_PATH . 'components/crossmanagerhybridauth/';
        $assetsUrl = MODX_ASSETS_URL . 'components/crossmanagerhybridauth/';

        $this->config = array_merge([
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'processorsPath' => $corePath . 'processors/',
            'customPath' => $corePath . 'providers/',

            'connectorUrl' => $assetsUrl . 'connector.php',
            'assetsUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl . 'css/',
            'jsUrl' => $assetsUrl . 'js/',
            'groups' => 'Administrator',
            'add_contexts' => 'mgr',
        ], $config);

        $this->modx->addPackage('crossmanagerhybridauth', $this->config['modelPath']);
        $this->modx->lexicon->load('crossmanagerhybridauth:default');

    }

    /**
     * Initializes component into different contexts.
     *
     * @param string $ctx The context to load. Defaults to web.
     * @param array $scriptProperties Properties for initialization.
     *
     * @return bool
     */
    public function initialize($ctx = 'web', $scriptProperties = array())
    {
        $this->config = array_merge($this->config, $scriptProperties);
        return true;
    }


    /**
     * Shorthand for the call of processor
     *
     * @access public
     *
     * @param string $action Path to processor
     * @param array $data Data to be transmitted to the processor
     *
     * @return mixed The result of the processor
     */
    public function runProcessor($action = '', $data = array())
    {
        if (empty($action)) {
            return false;
        }
        #$this->modx->error->reset();
        $processorsPath = !empty($this->config['processorsPath'])
            ? $this->config['processorsPath']
            : MODX_CORE_PATH . 'components/crossmanagerhybridauth/processors/';

        return $this->modx->runProcessor($action, $data, array(
            'processors_path' => $processorsPath,
        ));
    }

    /**
     * Обработчик для событий
     * @param modSystemEvent $event
     * @param array $scriptProperties
     */
    public function loadHandlerEvent(modSystemEvent $event, $scriptProperties = array())
    {
        switch ($event->name) {
            case 'OnHandleRequest':
                if ($this->modx->context->key !== 'mgr' && !$this->modx->user->id) {
                    if ($user = $this->modx->getAuthenticatedUser($this->modx->context->key)) {
                        $this->modx->user = $user;
                        $this->modx->getUser($this->modx->context->key);
                    }
                }

                if ($this->modx->user->isAuthenticated($this->modx->context->key)) {
                    if (!$this->modx->user->active || $this->modx->user->Profile->blocked) {
                        $this->modx->runProcessor('security/logout');
                        $this->modx->sendRedirect($this->modx->makeUrl($this->modx->getOption('site_start'), '', '', 'full'));
                    }
                }


                if (!empty($_REQUEST['hauth_action']) || !empty($_REQUEST['hauth_done'])) {

                    $this->loadHybridAuth();

                    if (!empty($_REQUEST['hauth_action'])) {
                        switch ($_REQUEST['hauth_action']) {
                            case 'login':
                                $provider = $this->getOption('provider_name');
                                if (!empty($provider)) {
                                    $this->Login($provider);
                                } else {
                                    $this->Refresh();
                                }
                                break;
                            case 'logout':
                                $this->Logout();
                                break;
                            case 'unbind':
                                if (!empty($_REQUEST['provider'])) {
                                    $this->runProcessor('web/service/remove', array(
                                        'provider' => $_REQUEST['provider'],
                                    ));
                                }
                                $this->Refresh();
                                break;
                        }
                    } else {
                        $this->Login($_REQUEST['hauth_done']);
                    }
                }
                break;

            case 'OnWebAuthentication':
                $this->modx->event->_output = !empty($_SESSION['cmHybridAuth']['verified']);
                unset($_SESSION['cmHybridAuth']['verified']);
                break;
            case 'OnManagerLoginFormPrerender':
                $enable_oauth2 = $this->getOption('enable_oauth2');
                if (!empty($enable_oauth2) or !empty($_GET['oauth2'])) {
                    // Регистрация иконки на странице с менеджером
                    $site_url = $this->modx->getOption('site_url') . 'manager/?hauth_action=login';
                    $provider_name = $this->getOption('provider_name');
                    $provider_logo = $this->getOption('provider_logo');
                    $op = '<script type="text/javascript">
                    Ext.onReady(function() {
                        var parent = document.createElement("div");
                       parent.setAttribute("style","text-align:center");
                        var a = document.createElement("a");
                        a.setAttribute("href","' . $site_url . '");
                        
                        var img = document.createElement("img");
                        img.setAttribute("src","' . $provider_logo . '");
                        img.setAttribute("alt","Войти через провайдера ' . $provider_name . '");
                        a.append(img);
                        parent.append(a);
                       document.getElementById("container").append(parent);
                    });
                    </script>';

                    $msg = $this->getErrorMsg();

                    
                    if (!empty($msg)) {
                        $msg = 'Произошла ошибка обратитесь к администратору сайта (подробности в логах).';
                        $op .= '<script type="text/javascript">
                        Ext.onReady(function() {
                            var parent = document.createElement("div");
                            parent.setAttribute("style","text-align: center;color: #b51b1b;max-width: 200px;margin: 0 auto;padding: 9px 0;");
                            parent.innerText = "' . $msg . '";
                           document.getElementById("container").append(parent);
                        });
                        </script>';

                    }
                    $this->modx->event->output($op);
                }
                break;
        }

    }

    public function getErrorMsg()
    {
        return !empty($_SESSION['cmHybridAuth']['error']) ? $_SESSION['cmHybridAuth']['error'] : '';
    }

    /**
     * @param $key
     * @param null $options
     * @param null $default
     * @param false $skipEmpty
     * @return array|mixed|null
     */
    public function getOption($key, $options = null, $default = null, $skipEmpty = false)
    {
        return $this->modx->getOption('crossmanagerhybridauth_' . $key, $options, $default, $skipEmpty);
    }


    /**
     * Method loads custom classes from specified directory
     *
     * @return void
     * @var string $dir Directory for load classes
     *
     */
    public function loadProviderClasses()
    {
        $files = scandir($this->config['customPath']);
        foreach ($files as $file) {
            if (preg_match('/.*?\.php$/i', $file)) {
                include_once($this->config['customPath'] . '/' . $file);
            }
        }
    }

    /**
     * Loads HybridAuth adapters
     */
    public function loadHybridAuth()
    {
        if (!class_exists('OAuth2')) {
            require_once MODX_CORE_PATH . 'components/crossmanagerhybridauth/vendor/autoload.php';
        }


        $provider = $this->getOption('provider_name');
        $client_id = $this->getOption('provider_client_id');
        $secret = $this->getOption('provider_secret');


        $this->loadProviderClasses();


        $class = '\Hybridauth\Provider\\' . $provider;
        if (class_exists($class)) {
            $config = [
                'keys' => [
                    'id' => $client_id,
                    'secret' => $secret,
                ],
            ];
            try {
                $config['callback'] = $this->modx->getOption('site_url') . '?hauth.done=' . $provider;
                $this->adapters[$provider] = new $class($config);
            } catch (Exception $e) {
                $this->exceptionHandler($e);
            }
        }
        $_SESSION['HA'] = [];
    }

    /**
     * Custom exception handler for Hybrid_Auth
     *
     * @param Throwable $e
     */
    public function exceptionHandler(Throwable $e)
    {
        $code = $e->getCode();
        if ($code <= 6) {
            $level = modX::LOG_LEVEL_ERROR;
        } else {
            $level = modX::LOG_LEVEL_INFO;
        }
        $msg = $e->getMessage();
        $_SESSION['cmHybridAuth']['error'] = $msg;
        $this->modx->log($level, '[HybridAuth] ' . $msg);
    }

    /**
     * Checks and login user. Also creates/updated user services profiles
     *
     * @param string $provider Remote service to login
     */
    public function Login($provider)
    {
        $context = $this->modx->context->key;
        try {
            if (isset($this->adapters[$provider])) {
                /** @var OAuth2 $adapter */

                $adapter = $this->adapters[$provider];
                $adapter->authenticate();
            }
        } catch (Exception $e) {
            $this->exceptionHandler($e);
        }

        if (empty($adapter) || !$adapter->isConnected()) {
            $this->Refresh('login');
            return;
        }

        unset($_SESSION['cmHybridAuth']['error']);
        try {
            if ($profile = $adapter->getUserProfile()) {
                $profile = json_decode(json_encode($profile), true);
            }
        } catch (Exception $e) {
            $this->exceptionHandler($e);
            $this->Refresh('login');
        }

        $profile['provider'] = $provider;


        /** @var haUserService $service */
        $service = $this->modx->getObject('cmhaUserService', [
            'identifier' => $profile['identifier'],
            'provider' => $profile['provider'],
        ]);
        if (!$service) {
            // Adding new record to current user

            if ($this->modx->user->isAuthenticated($context)) {
                $uid = $this->modx->user->id;
                $profile['internalKey'] = $uid;

                $response = $this->runProcessor('web/service/create', $profile);
                if ($response->isError()) {
                    $msg = implode(', ', $response->getAllErrors());
                    $this->modx->log(modX::LOG_LEVEL_ERROR,
                        '[HybridAuth] unable to save service profile for user ' . $uid . '. Message: ' . $msg
                    );
                    $_SESSION['cmHybridAuth']['error'] = $msg;
                }
            } else {

                // Create a new user and add this record to him
                $username = !empty($profile['identifier']) ?
                    trim($profile['identifier'])
                    : md5(rand(8, 10));


                if ($exists = $this->modx->getCount('modUser', ['username' => $username])) {
                    for ($i = 1; $i <= 10; $i++) {
                        $tmp = $username . $i;
                        if (!$this->modx->getCount('modUser', ['username' => $tmp])) {
                            $username = $tmp;
                            break;
                        }
                    }
                }

                $arr = [
                    'username' => $username,
                    /*'fullname' => !empty($profile['lastName'])
                        ? $profile['firstName'] . ' ' . $profile['lastName']
                        : $profile['firstName'],*/
                    'fullname' => $username,
                    'dob' => !empty($profile['birthDay']) && !empty($profile['birthMonth']) && !empty($profile['birthYear'])
                        ? $profile['birthYear'] . '-' . $profile['birthMonth'] . '-' . $profile['birthDay']
                        : '',
                    'email' => !empty($profile['emailVerified'])
                        ? $profile['emailVerified']
                        : $profile['email'],
                    'photo' => !empty($profile['photoURL'])
                        ? $profile['photoURL']
                        : '',
                    'website' => !empty($profile['webSiteURL'])
                        ? $profile['webSiteURL']
                        : '',
                    'phone' => !empty($profile['phone'])
                        ? $profile['phone']
                        : '',
                    'address' => !empty($profile['address'])
                        ? $profile['address']
                        : '',
                    'country' => !empty($profile['country'])
                        ? $profile['country']
                        : '',
                    'state' => !empty($profile['region'])
                        ? $profile['region']
                        : '',
                    'city' => !empty($profile['city'])
                        ? $profile['city']
                        : '',
                    'zip' => !empty($profile['zip'])
                        ? $profile['zip']
                        : '',
                    'active' => 1,
                    'provider' => $profile,
                    'groups' => $this->config['groups'],
                ];


                if (!$this->modx->getOption('crossmanagerhybridauth_register_users', null, true)) {
                    $_SESSION['cmHybridAuth']['error'] = $this->modx->lexicon('ha_register_disabled');
                } else {
                    $response = $this->runProcessor('web/user/create', $arr);
                    if ($response->isError()) {
                        $msg = implode(', ', $response->getAllErrors());
                        $this->modx->log(modX::LOG_LEVEL_ERROR,
                            '[HybridAuth] Unable to create user ' . print_r($arr, 1) . '. Message: ' . $msg
                        );
                        $_SESSION['cmHybridAuth']['error'] = $msg;
                    } else {

                        $this->modx->error->reset();
                        $login_data = [
                            'username' => $response->response['object']['username'],
                            'password' => md5(rand()),
                            'rememberme' => $this->config['rememberme'],
                        ];
                        $uid = $response->response['object']['id'];
                        $profile['internalKey'] = $uid;
                        $response = $this->runProcessor('web/service/create', $profile);
                        if ($response->isError()) {
                            $msg = implode(', ', $response->getAllErrors());
                            $this->modx->log(modX::LOG_LEVEL_ERROR,
                                '[HybridAuth] unable to save service profile for user ' . $uid . '. Message: ' . $msg
                            );
                            $_SESSION['cmHybridAuth']['error'] = $msg;
                        }
                    }
                }
            }
        } else {
            // Find and use connected MODX user
            if ($this->modx->user->isAuthenticated($context)) {
                $uid = $this->modx->user->id;
            } else {
                $uid = $service->get('internalKey');
            }

            /** @var modUser $user */
            if ($user = $this->modx->getObject('modUser', $uid)) {
                $login_data = [
                    'username' => $user->get('username'),
                    'password' => md5(rand()),
                    'rememberme' => $this->config['rememberme'],
                ];
                $profile['id'] = $service->get('id');
                $profile['internalKey'] = $uid;
                $response = $this->runProcessor('web/service/update', $profile);
                if ($response->isError()) {
                    $msg = implode(', ', $response->getAllErrors());
                    $this->modx->log(modX::LOG_LEVEL_ERROR,
                        '[HybridAuth] unable to update service profile for user ' . $uid . '. Message: ' . $msg);
                    $_SESSION['cmHybridAuth']['error'] = $msg;
                }
            } else {
                $service->remove();
                $this->Login($provider);
            }
        }

        $this->modx->error->reset();
        if (empty($_SESSION['cmHybridAuth']['error']) && !$this->modx->user->isAuthenticated($context) && !empty($login_data)) {
            $_SESSION['HA']['verified'] = 1;
            if (!empty($this->config['loginContext'])) {
                $login_data['login_context'] = $this->config['loginContext'];
            }
            if (!empty($this->config['addContexts'])) {
                $login_data['add_contexts'] = $this->config['addContexts'];
            }


            /* @var modUser $User */
            if ($User = $this->modx->getObject('modUser', ['username' => $login_data['username']])) {
                $User->addSessionContext('mgr');
            } else {
                $msg = "Не удалось найти пользователя" . $login_data['username'];
                $this->modx->log(modX::LOG_LEVEL_ERROR, $msg, '', __METHOD__, __FILE__, __LINE__);
                $_SESSION['cmHybridAuth']['error'] = $msg;
            }
        
            
            #
            // Login
            /* $_SESSION['cmHybridAuth']['verified'] = true;
             $response = $this->modx->runProcessor('security/login', $login_data);
             if ($response->isError()) {
                 $msg = implode(', ', $response->getAllErrors());
                 $this->modx->log(modX::LOG_LEVEL_ERROR,
                     '[HybridAuth] error login for user ' . $login_data['username'] . '. Message: ' . $msg);
                 $_SESSION['cmHybridAuth']['error'] = $msg;
             }*/
        }

        $this->Refresh('login');
    }


    /**
     * Destroys all sessions
     *
     * @return void
     */
    public function Logout()
    {
        try {
            /** @var OAuth2 $adapter */
            foreach ($this->adapters as $adapter) {
                $adapter->disconnect();
            }
        } catch (Exception $e) {
            $this->exceptionHandler($e);
        }

        $logout_data = [];
        if (!empty($this->config['loginContext'])) {
            $logout_data['login_context'] = $this->config['loginContext'];
        }
        if (!empty($this->config['addContexts'])) {
            $logout_data['add_contexts'] = $this->config['addContexts'];
        }

        $response = $this->modx->runProcessor('security/logout', $logout_data);
        if ($response->isError()) {
            $msg = implode(', ', $response->getAllErrors());
            $this->modx->log(modX::LOG_LEVEL_ERROR,
                '[HybridAuth] logout error. Username: ' . $this->modx->user->get('username') . ', uid: ' . $msg);
            $_SESSION['cmHybridAuth']['error'] = $msg;
        }
        $this->Refresh('logout');
    }


    /**
     * Refreshes the current page. If set, can redirects user to logout/login resource.
     *
     * @param string $action The action to do
     *
     * @return void
     */
    public function Refresh($action = '')
    {
        $url = MODX_MANAGER_URL;
        /*if ($action === 'login' && !empty($this->config['loginResourceId'])) {
            if ($resource = $this->modx->getObject('modResource', (int)$this->config['loginResourceId'])) {
                $url = $this->modx->makeUrl($resource->id, $resource->context_key, '', 'full');
            }
        } elseif ($action === 'logout' && !empty($this->config['logoutResourceId'])) {
            if ($resource = $this->modx->getObject('modResource', (int)$this->config['logoutResourceId'])) {
                $url = $this->modx->makeUrl($resource->id, $resource->context_key, '', 'full');
            }
        }*/

        if (empty($url)) {
            $request = preg_replace('#^' . $this->modx->getOption('base_url') . '#', '', $_SERVER['REQUEST_URI']);
            $url = $this->modx->getOption('site_url') . ltrim($request, '/');
            if ($pos = strpos($url, '?')) {
                $arr = explode('&', substr($url, $pos + 1));
                $url = substr($url, 0, $pos);
                if (count($arr) > 1) {
                    foreach ($arr as $k => $v) {
                        if (preg_match('#(action|provider|hauth.action|hauth.done|state|code|error|error_description)+#i', $v, $matches)) {
                            unset($arr[$k]);
                        }
                    }
                    if (!empty($arr)) {
                        $url = $url . '?' . implode('&', $arr);
                    }

                }
            }
        }
        $this->modx->sendRedirect($url);
    }

}