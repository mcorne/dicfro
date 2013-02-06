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

require_once 'Model/Search/Jeanneau.php';

/**
 * Jeanneau Search class tests
 *
 * @category   Application
 * @package    DicFro
 * @subpackage Tests
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2013 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class JeanneauSearchTest extends PHPUnit_Framework_TestCase
{
    /**
     * The Search class instance
     * @var Model_Search_Jeanneau
     */
    public $search;

    /**
     * Prepares a test
     */
    protected function setUp()
    {
        $this->search = new Model_Search_Jeanneau(APPLICATION_DIR . '/data');
    }

    /**
     * Tests searchWord
     */
    public function testSearchWord()
    {
        $this->assertSame(
            'http://www.prima-elementa.fr/Dico-a01.html#a',
            $this->search->searchWord('a'),
             'searching valid word');

        /**********/

        $this->assertSame(
            'http://www.prima-elementa.fr/Dico-o03.html#oe',
            $this->search->searchWord('œ'),
             'searching non ascii word');

        /**********/

        $this->assertSame(
            'http://www.prima-elementa.fr/Dico-a01.html#',
            $this->search->searchWord(''),
             'searching empty word');

        /**********/

        $this->assertSame(
            'http://www.prima-elementa.fr/Dico-a01.html#',
            $this->search->searchWord('w'),
             'searching invalid word');

        /**********/

        $this->assertSame(
            'http://www.prima-elementa.fr/Dico-t01.html#t',
            $this->search->searchWord('t'),
             'searching word before top word');

        /**********/

        $this->assertSame(
            'http://www.prima-elementa.fr/Dico-c14.html#cy',
            $this->search->searchWord('cy'),
             'searching word after last word');

        /**********/

        $this->assertSame(
            'http://www.prima-elementa.fr/Dico-b.htm#boo',
            $this->search->searchWord('boo'),
             'searching word in a single page');
    }
}