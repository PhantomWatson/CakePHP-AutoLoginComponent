<?php
namespace AutoLogin\Test\TestCase\Controller\Component;

use AutoLogin\Controller\Component;
use Cake\TestSuite\TestCase;
use Cake\Controller\Controller;
use Cake\Controller\ComponentRegistry;
use Cake\Network\Request;
use Cake\Network\Response;

class AutoLoginComponentTest extends TestCase
{

    public $component = null;
    public $controller = null;

    public function setUp()
    {
        parent::setUp();
        $request = new Request();
        $response = new Response();
        $this->controller = $this->getMock(
            'Cake\Controller\Controller',
            [],
            [$request, $response]
        );
        $registry = new ComponentRegistry($this->controller);
        $this->component = new AutoLoginComponent($registry);
    }

    // Plugin fixtures located in /plugins/Blog/tests/Fixture/
    //public $fixtures = ['plugin.blog.blog_posts'];

    public function testSetCookie()
    {
        $loginData = [
            'email' => 'example@example.com',
            'password' => 'arbitrary password'
        ];

        // Test setting cookie from request data
        $this->controller->request->data = $loginData;
        $cookieKey = $this->component->config('cookieKey');
        $this->component->setCookie();
        $result = $this->component->Cookie->read("$cookieKey");
        $expected = $loginData;
        $this->assertEquals($expected, $result);
    }
}
