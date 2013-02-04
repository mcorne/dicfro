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
require_once 'View/Helper/Verbs.php';

/**
 * Verbs view helper class tests
 *
 * @category   Application
 * @package    DicFro
 * @subpackage Tests
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2010 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class VerbsTest extends PHPUnit_Framework_TestCase
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

        $this->viewHelper = new View_Helper_Verbs($view);
    }

    /**
     * Tests cleanOriginalVerb
     */
    public function testCleanOriginalVerb()
    {
        $this->assertSame(
            'abc',
            $this->viewHelper->cleanOriginalVerb('abc*'),
             'cleaning verb');
   }

    /**
     * Tests convertPersons
     */
    public function testConvertPersons()
    {
        $this->assertSame(
            '(je)',
            $this->viewHelper->convertPersons('1'),
             'converting person');

        /**********/

        $this->assertSame(
            '(?)',
            $this->viewHelper->convertPersons('9'),
             'converting invalid person');
   }

    /**
     * Tests convertTense
     */
    public function testConvertTense()
    {
        $this->assertSame(
            'Infinitif',
            $this->viewHelper->convertTense('inf.'),
             'converting tense');

        /**********/

        $this->assertSame(
            'xyz (?)',
            $this->viewHelper->convertTense('xyz'),
             'converting invalid tense');
   }

    /**
     * Tests extractComposedVerbs
     *
     * @depends testCleanOriginalVerb
     */
    public function testExtractComposedVerbs()
    {
        $this->assertSame(
            array(),
            $this->viewHelper->extractComposedVerbs(true),
             'extracting no verbs');

        /**********/

        $this->viewHelper->view->composedVerbs = array(
            array('composed' => true,  'infinitive' => 'abc', 'original' => 'ABC'),
            array('composed' => true,  'infinitive' => 'abc', 'original' => 'abc'),
            array('composed' => true,  'infinitive' => 'def', 'original' => 'DEF'),
            array('composed' => false, 'infinitive' => 'ghi', 'original' => 'GHI'),
        );

        $this->assertSame(
            array(
                'abc' => array(
                    array('value' => 'ABC', 'text' => 'ABC'),
                    array('value' => 'abc', 'text' => 'abc'),
                ),
                'def' => array(
                    array('value' => 'DEF', 'text' => 'DEF'),
                ),
            ),
            $this->viewHelper->extractComposedVerbs(true),
             'extracting composed verbs');

        /**********/

        $this->assertSame(
            array(
                'ghi' => array(
                    array('value' => 'GHI', 'text' => 'GHI'),
                ),
            ),
            $this->viewHelper->extractComposedVerbs(false),
             'extracting model verbs');
   }

    /**
     * Tests formatVerbForm
     *
     * @depends testConvertPersons
     */
    public function testFormatVerbForm()
    {
        $this->assertSame(
            '(je) abc, def',
            $this->viewHelper->formatVerbForm(array(
                'person' => '1',
                'original' => 'abc',
                'tense' => 'def',
            )),
             'format verb form with person');

        /**********/

        $this->assertSame(
            'abc, def',
            $this->viewHelper->formatVerbForm(array(
                'person' => '',
                'original' => 'abc',
                'tense' => 'def',
            )),
             'format infinitive verb form');
   }

    /**
     * Tests extractIdentifiedVerbs
     *
     * @depends testFormatVerbForm
     */
    public function testExtractIdentifiedVerbs()
    {
        $this->assertSame(
            array(),
            $this->viewHelper->extractIdentifiedVerbs(),
             'extracting no identified verbs');

        /**********/

        $this->viewHelper->view->identifiedVerbs = array(
            array('person' => '1',  'infinitive' => 'abc', 'original' => 'ABC', 'tense' => 'a'),
            array('person' => '',   'infinitive' => 'abc', 'original' => 'abc', 'tense' => 'b'),
            array('person' => '1',  'infinitive' => 'def', 'original' => 'DEF', 'tense' => 'c'),
        );

        $this->assertSame(
            array(
                'abc' => array(
                    array('value' => 'abc', 'text' => '(je) ABC, a'),
                    array('value' => 'abc', 'text' => 'abc, b'),
                ),
                'def' => array(
                    array('value' => 'def', 'text' => '(je) DEF, c'),
                ),
            ),
            $this->viewHelper->extractIdentifiedVerbs(),
             'extracting identified verbs');
   }

    /**
     * Tests extractInfinitives
     */
    public function testExtractInfinitives()
    {
        $this->assertSame(
            'abc, def',
            $this->viewHelper->extractInfinitives(array(
                'abc' => array(),
                'def' => array(),
            )),
             'extracting infinitives');
   }

    /**
     * Tests replacePersons
     */
    public function testReplacePersons()
    {
        $this->assertSame(
            '(je) abc, (tu) def',
            $this->viewHelper->replacePersons('1: abc, 2: def'),
             'replacing persons');
   }

    /**
     * Tests presetTenses
     */
    public function testPresetTenses()
    {
        $this->viewHelper->tenses = array('def' => 'DEF', 'abc' => 'ABC');

        $this->assertSame(
            array('def' => null, 'abc' => null),
            $this->viewHelper->presetTenses(),
             'sorting tenses');
   }

    /**
     * Tests getConjugationTables
     *
     * @depends testPresetTenses
     * @depends testConvertTense
     * @depends testReplacePersons
     */
    public function testGetConjugationTables()
    {
        $this->viewHelper->view->tcaf = array(
            array('original' => 'abc', 'tense' => 'inf.', 'conjugation' => 'a'),
            array('original' => 'abc', 'tense' => 'cond.', 'conjugation' => 'b'),
            array('original' => 'ABC', 'tense' => 'inf.', 'conjugation' => 'c'),
        );

        $this->assertSame(
            array(
                'abc' => array(
                    'inf.' => array('tense' => 'Infinitif', 'conjugation' => 'a'),
                    'cond.' => array('tense' => 'Conditionnel', 'conjugation' => 'b'),
                ),
                'ABC' => array(
                    'inf.' => array('tense' => 'Infinitif', 'conjugation' => 'c'),
                ),
            ),
            $this->viewHelper->getConjugationTables(),
             'conjugating verb');
   }
}