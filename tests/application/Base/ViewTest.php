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
 * @copyright  2008-2013 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

require_once "PHPUnit/Framework/TestCase.php";
require_once "PHPUnit/Framework/TestSuite.php";

require_once 'Test.php';

require_once 'Base/View.php';

/**
 * View class tests
 *
 * @category   Application
 * @package    DicFro
 * @subpackage Tests
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2013 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class ViewTest extends PHPUnit_Framework_TestCase
{
    /**
     * The View class instance
     * @var Base_View
     */
    public $view;

    /**
     * Prepares a test
     */
    protected function setUp()
    {
        Test::createTempDir();
        $_SERVER['HTTP_HOST'] = 'abc';

        $config = array(
            'data-dir'       => Test::getTempDir(),
            'domain-subpath' => 'def',
            'views-dir'      => 'ghi',
        );

        $this->view = new Base_View($config, false);
    }

    /**
     * Tests setBaseUrl
     */
    public function testSetBaseUrl()
    {
        $this->view->setBaseUrl();

        $this->assertSame(
            'http://abc/def',
            $this->view->baseUrl,
            'setting the base URL');

        /**********/

        $this->view->config = array();

        $this->view->setBaseUrl();

        $this->assertSame(
            'http://abc',
            $this->view->baseUrl,
            'setting default base URL');
    }

    /**
     * Tests init
     *
     * @depends testSetBaseUrl
     */
    public function testInit()
    {
        $this->assertSame(
            array(
                'ghi',
                'http://abc/def',
                'View_Helper_Dictionaries',
                'View_Helper_Images',
                'View_Helper_Verbs',
                'View_Helper_Words'
            ),
            array(
                $this->view->viewsDir,
                $this->view->baseUrl,
                get_class($this->view->dictionariesHelper),
                get_class($this->view->imagesHelper),
                get_class($this->view->verbsHelper),
                get_class($this->view->wordsHelper),
            ),
            'initializing the view');
    }

    /**
     * Tests render
     */
    public function testRender()
    {
        $this->view->viewsDir = Test::getTempDir();
        @mkdir(Test::getTempDir() . '/interface');
        file_put_contents(Test::getTempDir() . '/interface/layout.phtml', 'abc');

        ob_start();
        $this->view->render();
        $content = ob_get_clean();

        $this->assertSame(
            'abc',
            $content,
            'default rendering');

        /**********/

        file_put_contents(Test::getTempDir() . '/test.phtml', 'abc');

        ob_start();
        $this->view->render('test.phtml');
        $content = ob_get_clean();

        $this->assertSame(
            'abc',
            $content,
            'rendering view');
    }

    /**
     * Tests setLinkUrl
     */
    public function testSetLinkUrl()
    {
        $this->view->baseUrl = 'abc';

        $this->assertSame(
            'abc/def',
            $this->view->setLinkUrl('def'),
            'setting link URL');
    }

    /**
     * Tests toArray
     */
    public function testToArray()
    {
        $this->assertSame(
            array(
            ),
            $this->view->toArray(),
            'converting no dynamic properties to array');

        /**********/

        $this->view->def = 'DEF';
        $this->view->abc = 'ABC';

        $this->assertSame(
            array(
                'abc' => 'ABC',
                'def' => 'DEF',
            ),
            $this->view->toArray(),
            'converting dynamic properties to array');

        /**********/

       $this->assertSame(
            array(
                'abc' => 'ABC',
            ),
            $this->view->toArray(false, 'def'),
            'converting some dynamic properties to array');

        /**********/

        $this->view->ghi = '';

        $this->assertSame(
            array(
                'abc' => 'ABC',
                'def' => 'DEF',
            ),
            $this->view->toArray(true),
            'converting no empty dynamic properties to array ');
    }
}
