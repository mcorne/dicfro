<?php
/**
 * Dicfro
 *
 * PHP 5
 *
 * @category   Application
 * @package    DicFro
 * @subpackage Tests
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2010 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

require_once "PHPUnit/Framework/TestCase.php";
require_once "PHPUnit/Framework/TestSuite.php";

require_once 'Controller/Front.php';

/**
 * Front controller class tests
 *
 * @category   Application
 * @package    DicFro
 * @subpackage Tests
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2010 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class FrontTest extends PHPUnit_Framework_TestCase
{
    /**
     * The Front controller class instance
     * @var object
     */
    public $front;

    /**
     * Prepares a test
     */
    protected function setUp()
    {
        $config = array(
            'domain-subpath' => 'base',
            'dictionaries' => array('uvw' => array('language' => 'fr')),
        );

        $view = new Base_View($config, false);

        $this->front = new Controller_Front($config, $view);
    }

    /**
     * Tests setAction
     */
    public function testSetAction()
    {
        $this->front->actionParams = array('action', 'param');

        $this->front->setAction();

        $this->assertSame(
            array(
                'action',
                array('param'),
            ),
            array($this->front->action, $this->front->actionParams),
            'setting action');

        /**********/

        $this->front->actionParams = array();

        $this->front->setAction();

        $this->assertSame(
            array(
                '',
                array(),
            ),
            array($this->front->action, $this->front->actionParams),
            'setting no action');
    }

    /**
     * Tests matchRoute
     *
     * @depends testSetAction
     */
    public function testMatchRoute()
    {
        $_SERVER['REQUEST_URI'] = '/base/action/dict/123/456/word';

        $this->front->matchRoute();

        $this->assertSame(
            array(
                'action',
                array(
                    'dict',
                    '123',
                    '456',
                    'word',
                ),
            ),
            array($this->front->action, $this->front->actionParams),
            'matching route');

        /**********/

        $_SERVER['REQUEST_URI'] = '/base';

        $this->front->matchRoute();

        $this->assertSame(
            array(
                '',
                array(),
            ),
            array($this->front->action, $this->front->actionParams),
            'matching minimal route');

        /**********/

        $this->front->config['domain-subpath'] = '';

        $this->front->matchRoute();

        $this->assertSame(
            array(
                'base',
                array(),
            ),
            array($this->front->action, $this->front->actionParams),
            'matching with no base URL');
    }

    /**
     * Tests callController
     */
    public function testCallController()
    {
        $_SERVER['REQUEST_URI'] = '/base';
        $_SERVER['HTTP_USER_AGENT'] = '';
        $this->front->controllerName = 'interface';
        $this->front->actionParams = array('uvw', '123', '456', 'abc', 'def');

        $this->front->callController();

        $this->assertSame(
            array(
                'aboutLink' => 'a-propos',
                'autoSearch' => false,
                'dictionariesLink' => 'liste-des-dictionnaires',
                'dictionary' => array (
                    'language' => 'fr',
                    'id' => 'uvw',
                    'introduction' => 'uvw.phtml',
                    'url' => 'uvw',
                ),
                'dictionaryLink' => 'chercher/%s/abc',
                'domainSubpath' => 'base',
                'goPageLink' => 'aller-page/uvw/%s/abc',
                'helpLink' => 'aide',
                'homeLink' => 'accueil',
                'information' => 'information/home.phtml',
                'introductionLink' => 'introduction/uvw',
                'isIE' => false,
                'newTab' => false,
                'nextPageLink' => 'page-suivante/uvw/123/456/abc',
                'noDictChange' => false,
                'optionsLink' => 'options',
                'page' => '123',
                'previousPageLink' => 'page-precedente/uvw/123/456/abc',
                'volume' => '456',
                'word' => 'abc',
                'wordLink' => 'chercher/uvw/%s',
            ),
            $this->front->view->toArray(),
            'calling controller');
    }

    /**
     * Tests setParams
     */
    public function testSetParams()
    {
        $_GET = array(); // must empty because it is set if run within ZS in CGI mode
        $this->front->setParams();

        $this->assertSame(
            array(),
            $this->front->params,
            'setting no parameters');

        /**********/

        $_GET = array('abc' => 123, 'def' => 456);

        $this->front->setParams();

        $this->assertSame(
            $_GET,
            $this->front->params,
            'setting parameters');
    }

    /**
     * Tests run
     *
     * @depends testMatchRoute
     * @depends testSetParams
     * @depends testCallController
     */
    public function testRun()
    {
        $_SERVER['REQUEST_URI'] = '/base/action/uvw/123/456/abc';
        $_SERVER['HTTP_USER_AGENT'] = '';

        $this->front->run();

        $this->assertSame(
            array(
                'aboutLink' => 'a-propos',
                'autoSearch' => false,
                'dictionariesLink' => 'liste-des-dictionnaires',
                'dictionary' => array (
                    'language' => 'fr',
                    'id' => 'uvw',
                    'introduction' => 'uvw.phtml',
                    'url' => 'uvw',
                ),
                'dictionaryLink' => 'chercher/%s/abc',
                'domainSubpath' => 'base',
                'goPageLink' => 'aller-page/uvw/%s/abc',
                'helpLink' => 'aide',
                'homeLink' => 'accueil',
                'information' => 'information/home.phtml',
                'introductionLink' => 'introduction/uvw',
                'isIE' => false,
                'newTab' => false,
                'nextPageLink' => 'page-suivante/uvw/123/456/abc',
                'noDictChange' => false,
                'optionsLink' => 'options',
                'page' => '123',
                'previousPageLink' => 'page-precedente/uvw/123/456/abc',
                'volume' => '456',
                'word' => 'abc',
                'wordLink' => 'chercher/uvw/%s',
            ),
            $this->front->view->toArray(),
            'running front controller');
    }
}