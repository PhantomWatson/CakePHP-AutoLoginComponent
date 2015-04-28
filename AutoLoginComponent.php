<?php
/**
 * @author Graham Watson <graham@phantomwatson.com>
 * @copyright 2015, Graham Watson
 * @license https://github.com/PhantomWatson/CakePHP-AutoLoginComponent/LICENSE.md MIT License
 * @link https://github.com/PhantomWatson/CakePHP-AutoLoginComponent
 */

namespace App\Controller\Component;

use Cake\Controller\Component;

class AutoLoginComponent extends Component {

    public $components = ['Cookie', 'Auth'];
    public $request;
    public $settings = [];
    protected $_defaultConfig = [
        'autoLogin' => true,
        'cookieKey' => 'autoLogin',
        'cookieSettings' => [],
        'fields' => [
            'email',
            'username',
            'password'
        ]
    ];

    public function initialize(array $config)
    {
        parent::initialize([]);
        $this->configureCookie();
    }

    public function startup(\Cake\Event\Event $event)
    {
        if ($this->config('autoLogin') == true && ! $this->Auth->user()) {
            $this->restoreLoginFromCookie();
        }
    }

    /**
     * Logs the user in using cookie
     *
     * @return boolean
     */
    public function restoreLoginFromCookie()
    {
        if (! $this->cookieIsSet()) {
            return false;
        }

        $fields = $this->config('fields');
        $cookieKey = $this->config('cookieKey');
        $loginData = [];

        foreach ($fields as $field) {
            if ($this->Cookie->check("$cookieKey.$field")) {
                $loginData[$field] = $this->Cookie->read("$cookieKey.$field");
            }
        }

        // $controller->request->data might need to be copied over to $this->request->data, depending on how Auth is looking for it
        $controller = $this->_registry->getController();
        $tempRequest = $controller->request->data;
        $controller->request->data = $loginData;
        $user = $this->Auth->identify();
        $controller->request->data = $tempRequest;

        if ($user) {
            $this->Auth->setUser($user);
            return true;
        }

        return false;
    }

    /**
     * Sets the cookie with the passed data, request data, or session data
     *
     * @param array $data
     * @return boolean
     */
    public function setCookie($data = [])
    {
        if (empty($data)) {
            $controller = $this->_registry->getController();
            $data = $controller->request->data;
        }
        if (empty($data)) {
            $data = $this->Auth->user();
        }
        if (empty($data)) {
            return false;
        }

        $fields = $this->config('fields');
        $cookieKey = $this->config('cookieKey');

        foreach ($fields as $field) {
            if (isset($data[$field]) && ! empty($data[$field])) {
                $this->Cookie->write("$cookieKey.$field", $data[$field]);
            }
        }

        return true;
    }

    /**
     * Returns true if the cookie has been set
     *
     * @return boolean
     */
    public function cookieIsSet()
    {
        $cookieKey = $this->config('cookieKey');
        return $this->Cookie->check($cookieKey);
    }

    /**
     * Removes the cookie
     */
    public function destroyCookie()
    {
        $cookieKey = $this->config('cookieKey');
        $this->Cookie->delete($cookieKey);
    }

    /**
     * Applies settings set in $this->config('settings')
     */
    public function configureCookie()
    {
        $settingFields = ['expires', 'path', 'domain', 'secure', 'key', 'httpOnly', 'encryption'];
        $settings = $this->config('settings');
        $cookieKey = $this->config('cookieKey');
        foreach ($settingFields as $field) {
            if (isset($settings[$field])) {
                $this->Cookie->configKey($cookieKey, $field, $settings[$field]);
            }
        }
    }
}
