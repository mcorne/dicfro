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
require_once 'View/Helper/Dictionaries.php';

/**
 * Dictionaries view helper class tests
 *
 * @category   Application
 * @package    DicFro
 * @subpackage Tests
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2013 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class DictionariesTest extends PHPUnit_Framework_TestCase
{
    /**
     * The view helper class instance
     * @var View_Helper_Dictionaries
     */
    public $viewHelper;

    /**
     * Prepares a test
     */
    protected function setUp()
    {
        $view = new Base_View(array(), false);

        $this->viewHelper = new View_Helper_Dictionaries($view);
    }

    /**
     * Tests getDescription
     */
    public function testGetDescription()
    {
        $this->viewHelper->view->dictionary = array('description' => 'abc');

        $this->assertSame(
            'abc',
            $this->viewHelper->getDescription(),
             'getting dictionary description');
   }

    /**
     * Tests getGroups
     */
    public function testgetGroups()
    {
        $this->viewHelper->view->config = array(
            'groups' => array(
                 array(
                     'name' => 'A',
                     'dictionaries' => array('abc', 'def'),
                 ),
                 array(
                     'name' => 'B',
                     'dictionaries' => array('ghi'),
                 )),

            'dictionaries' => array(
                'abc' => array('name' => 'abc', 'description' => 'ABC', 'url' => '123'),
                'def' => array('name' => 'def', 'description' => 'DEF'),
                'ghi' => array('name' => 'ghi', 'description' => 'GHI'),
            ),
        );

        $this->assertSame(
            array(
                array (
                    'label' => 'A',
                    'options' => array (
                        array (
                            'selected' => false,
                            'text' => 'abc',
                            'title' => 'ABC',
                            'value' => '123',
                        ),
                        array (
                            'selected' => false,
                            'text' => 'def',
                            'title' => 'DEF',
                            'value' => 'def',
                        ),
                    ),
                ),
                array (
                    'label' => 'B',
                    'options' => array (
                        array (
                            'selected' => false,
                            'text' => 'ghi',
                            'title' => 'GHI',
                            'value' => 'ghi',
                        ),
                    ),
                ),
            ),
            $this->viewHelper->getGroups(),
             'getting dictionaries groups');
   }

    /**
     * Tests getPageTitle
     */
    public function testGetPageTitle()
    {
        $this->viewHelper->view->dictionary = array('title' => 'abc');

        $this->assertSame(
            'abc',
            $this->viewHelper->getPageTitle(),
             'getting page title');

        /**********/

        $this->viewHelper->view->dictionary = array('name' => 'def');

        $this->assertSame(
            'def',
            $this->viewHelper->getPageTitle(),
             'getting default page title');
   }
}