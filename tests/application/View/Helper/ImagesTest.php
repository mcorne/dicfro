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

require_once 'Base/View.php';
require_once 'View/Helper/Images.php';

/**
 * Images view helper class tests
 *
 * @category   Application
 * @package    DicFro
 * @subpackage Tests
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2013 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class ImagesTest extends PHPUnit_Framework_TestCase
{
    /**
     * The view helper class instance
     * @var View_Helper_Images
     */
    public $viewHelper;

    /**
     * Prepares a test
     */
    protected function setUp()
    {
        $view = new Base_View(array(), false);

        $this->viewHelper = new View_Helper_Images($view);

        $this->setImages();
    }

    public function setImages()
    {
        $this->viewHelper->images = array(
            'default' => array('file' => 'default.jpg', 'source' => 'DEFAULT'),
            'abc' =>     array('file' => 'abc.jpg',     'source' => 'ABC'),
            'def' =>     array('file' => 'def.jpg',     'source' => 'DEF'),
        );
    }

    /**
     * Tests getImage
     */
    public function testGetImage()
    {
        $this->viewHelper->view->dictionary = array('file' => 'abc.gif');

        $this->assertSame(
            array('file' => 'abc.gif'),
            $this->viewHelper->getImage(),
             'getting dictionary image');

        /**********/

        $this->viewHelper->view->information = 'def.phtml';

        $this->assertSame(
            array('file' => 'def.jpg',     'source' => 'DEF'),
            $this->viewHelper->getImage(),
             'getting info page image');

        /**********/

        $this->viewHelper->view->information = 'xyz.phtml';

        $this->assertSame(
            array('file' => 'default.jpg', 'source' => 'DEFAULT'),
            $this->viewHelper->getImage(),
             'getting default image');
   }

    /**
     * Tests getImageFile
     *
     * @depends testGetImage
     */
    public function testGetImageFile()
    {
        $this->viewHelper->view->dictionary = array('file' => 'abc.gif');

        $this->assertSame(
            '/img/pages/abc.gif',
            $this->viewHelper->getImageFile(),
             'getting dictionary image');
   }

    /**
     * Tests getImageSourceUrl
     *
     * @depends testGetImage
     */
    public function testGetImageSourceUrl()
    {
        $this->viewHelper->view->information = 'def.phtml';

        $this->assertSame(
            'DEF',
            $this->viewHelper->getImageSourceUrl(),
             'getting image source URL');
   }
}