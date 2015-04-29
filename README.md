CakePHP AutoLogin Component
===========================

This uses CakePHP's Cookie component in order to store a user's login data and automatically restore any sessions after they expire.

Compatibility:
--------------

Tested in CakePHP 3.0.0. This component will not work with CakePHP versions 1.x or 2.x.

Installation:
-------------

 - Add `AutoLoginComponent.php` to the directory `src/Controller/Component`
 - Add `$this->loadComponent('AutoLogin');` to `AppController::initialize()`
 - Add `$this->AutoLogin->setCookie();` after `$this->Auth->setUser($user);` wherever you log your users in
 - Add `$this->AutoLogin->destroyCookie();` where you log your users out
 - It is **strongly recommended** that you use encrypted cookies. CakePHP 3 uses AES-encrypted cookies by default.

Configuration:
--------------

This component takes the following options:

 - **autoLogin** - If true, attempts to logs the user in with cookie data. (Default: `true`)
 - **cookieKey** - The name of the variable that the user's AutoLogin data is placed under in the cookie. (Default: `'autoLogin'`)
 - **cookieSettings** - An array of [cookie configuration settings](http://book.cakephp.org/3.0/en/controllers/components/cookie.html#configuring-cookies) for overriding the existing cookie configuration for the AutoLogin cookie variable. Possible options: 'expires', 'path', 'domain', 'secure', 'key', 'httpOnly', and 'encryption'. (Default: `[]`)
 - **fields** - The field names relevant to user authentication in your application. If your password field is called `'pass'`, you'll need to change this to something like `['username', 'pass']`. (Default: `['email', 'username', 'password']`)

For information about how to set these options, visit the [Configuring Components](http://book.cakephp.org/3.0/en/controllers/components.html#configuring-components) section of the CakePHP 3 docs.
