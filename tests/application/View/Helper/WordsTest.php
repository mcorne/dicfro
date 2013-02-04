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

require_once 'Base/View.php';
require_once 'View/Helper/Words.php';

/**
 * Words view helper class tests
 *
 * @category   Application
 * @package    DicFro
 * @subpackage Tests
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2010 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class WordsTest extends PHPUnit_Framework_TestCase
{
    /**
     * The view helper class instance
     * @var object
     */
    public $viewHelper;

    /**
     * Prepares a test
     */
    protected function setUp()
    {
        $view = new Base_View(array(), false);

        $this->viewHelper = new View_Helper_Words($view);
    }

    /**
     * Tests getLemmaAndPof
     */
    public function testGetLemmaAndPof()
    {
        $this->assertSame(
            'abc, def',
            $this->viewHelper->getLemmaAndPof(array('lemma' => 'abc', 'pof' => 'def')),
             'getting lemma and pof');

        /**********/

        $this->assertSame(
            'abc',
            $this->viewHelper->getLemmaAndPof(array('lemma' => 'abc')),
             'getting lemma');
   }

    /**
     * Tests getForms
     */
    public function testGetForms()
    {
        $this->assertSame(
            array(
                array(
                    'value' => 'ghi',
                    'text' => 'ghi : abc, def',
                ),
                array(
                    'value' => 'jkl',
                    'text' => 'jkl : abc, def',
                ),
            ),
            $this->viewHelper->getForms(
                array('lemma' => 'abc', 'pof' => 'def', 'main' => 'ghi; jkl'),
                'main'),
             'getting forms');

        /**********/

        $this->assertSame(
            array(
                array(
                    'value' => 'abc',
                    'text' => 'abc, def',
                ),
            ),
            $this->viewHelper->getForms(
                array('lemma' => 'abc', 'pof' => 'def')),
             'getting forms without type');
   }

    /**
     * Tests sortForms
     */
    public function testSortForms()
    {
        $this->assertSame(
            array(
                array('value' => 'abc', 'text' => 'def, ghi'),
                array('value' => 'jkl', 'text' => 'mno'),
            ),
            $this->viewHelper->sortForms(array(
                array('value' => 'jkl', 'text' => 'mno'),
                array('value' => 'abc', 'text' => 'def, ghi'),
            )),
             'sorting forms');
   }

    /**
     * Tests extractForms
     *
     * @depends testGetForms
     * @depends testSortForms
     */
    public function testExtractForms()
    {
        $this->viewHelper->view->identifiedWords = array(
            array('lemma' => 'abc', 'pof' => 'def', 'main' => 'ghi; jkl'),
            array('lemma' => 'mno', 'pof' => 'pqr', 'main' => 'stu'),
            array('lemma' => 'vwx', 'pof' => 'yza', 'xyz' => 'bcd'),
            array('lemma' => 'efg', 'pof' => 'hij'),
        );

        $this->assertSame(
            array(
                array('value' => 'ghi', 'text' => 'ghi : abc, def'),
                array('value' => 'jkl', 'text' => 'jkl : abc, def'),
                array('value' => 'stu', 'text' => 'stu : mno, pqr'),
            ),
            $this->viewHelper->extractForms('main'),
             'extracting forms');
   }

    /**
     * Tests extractOtherForms
     *
     * @depends testGetForms
     * @depends testSortForms
     */
    public function testExtractOtherForms()
    {
        $this->viewHelper->view->identifiedWords = array(
            array('lemma' => 'abc', 'pof' => 'def', 'main' => 'ghi; jkl'),
            array('lemma' => 'mno', 'pof' => 'pqr', 'main' => 'stu'),
            array('lemma' => 'vwx', 'pof' => 'yza', 'variants' => 'bcd'),
            array('lemma' => 'efg', 'pof' => 'hij'),
        );

        $this->assertSame(
            array(
                array('value' => 'efg', 'text' => 'efg, hij'),
            ),
            $this->viewHelper->extractOtherForms(),
             'extracting other forms');
   }
}