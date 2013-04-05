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

require_once 'Model/Query/Internal.php';

/**
 * Internal Query class tests
 *
 * @category   Application
 * @package    DicFro
 * @subpackage Tests
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2013 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class InternalTest extends PHPUnit_Framework_TestCase
{
    /**
     * The Query class instance
     * @var Model_Query_InternalExtended
     */
    public $query;

    /**
     * Prepares a test
     */
    protected function setUp()
    {
        Test::createTempDir();
        $this->query = new Model_Query_InternalExtended(Test::getTempDir());
        $this->setDatabase();
    }

    public function setDatabase()
    {
        $pdo = new PDO($this->query->dsn);
        $pdo->query('DROP TABLE IF EXISTS word');
        $pdo->query('CREATE TABLE word (ascii TEXT, image TEXT, original TEXT, previous TEXT, extra TEXT)');
        $pdo->query('CREATE INDEX ascii ON word (ascii ASC)');
        $pdo->query('CREATE INDEX image ON word (image ASC)');
        $pdo->query('INSERT INTO word (ascii, image, original, previous, extra) VALUES ("ABC", "1", "abc", "", "1")');
        $pdo->query('INSERT INTO word (ascii, image, original, previous, extra) VALUES ("ADE", "2", "ade", "ABC", "2")');
        $pdo->query('INSERT INTO word (ascii, image, original, previous, extra) VALUES ("DEF", "3", "def", "ADE", "3")');
        $pdo->query('INSERT INTO word (ascii, image, original, previous, extra) VALUES ("DEF", "4", "def", "DEF", "4")');
        $pdo->query('INSERT INTO word (ascii, image, original, previous, extra) VALUES ("DEF", "5", "def", "DEF", "5")');
        $pdo->query('INSERT INTO word (ascii, image, original, previous, extra) VALUES ("GHI", "6", "ghi", "DEF", "6")');
        $pdo->query('INSERT INTO word (ascii, image, original, previous, extra) VALUES ("KLM", "7", "klm", "GHI", "7")');
        $pdo->query('INSERT INTO word (ascii, image, original, previous, extra) VALUES ("OPQ", "8", "opq", "KLM", "8")');
        // print_r($pdo->query('select * from word')->fetchAll(PDO::FETCH_ASSOC));
    }

    /**
     * Tests goToFirstPage
     */
    public function testGoToFirstPage()
    {
        $this->assertSame(
            array(
                array('ascii' => 'ABC', 'image' => '1', 'original' => 'abc'),
                array('ascii' => 'ADE', 'image' => '2', 'original' => 'ade'),
            ),
            $this->query->goToFirstPage(),
             'going to first page');
    }

    /**
     * Tests goToLastPage
     */
    public function testGoToLastPage()
    {
        $this->assertSame(
            array(
                array('ascii' => 'OPQ', 'image' => '8', 'original' => 'opq'),
            ),
            $this->query->goToLastPage(),
             'going to last page');
    }

    /**
     * Tests goToPage
     * @depends testGoToFirstPage
     * @depends testGoToLastPage
     */
    public function testGoToPage()
    {
        $this->assertSame(
            array(
                array('ascii' => 'GHI', 'image' => '6', 'original' => 'ghi'),
                array('ascii' => 'KLM', 'image' => '7', 'original' => 'klm'),
            ),
            $this->query->goToPage('6'),
             'going to a page');

        /**********/

        $this->assertSame(
            array(
                array('ascii' => 'ABC', 'image' => '1', 'original' => 'abc'),
                array('ascii' => 'ADE', 'image' => '2', 'original' => 'ade'),
            ),
            $this->query->goToPage('0'),
             'trying to go to page before first page, going to first page instead');

        /**********/

        $this->assertSame(
            array(
                array('ascii' => 'OPQ', 'image' => '8', 'original' => 'opq'),
            ),
            $this->query->goToPage('9'),
             'trying to go to beyond last page, getting to last page instead');
    }

    /**
     * Tests goToNextPage
     * @depends testGoToLastPage
     */
    public function testGoToNextPage()
    {
        $this->assertSame(
            array(
                array('ascii' => 'GHI', 'image' => '6', 'original' => 'ghi'),
                array('ascii' => 'KLM', 'image' => '7', 'original' => 'klm'),
            ),
            $this->query->goToNextPage('5'),
             'going to next page');

        /**********/

        $this->assertSame(
            array(
                array('ascii' => 'OPQ', 'image' => '8', 'original' => 'opq'),
            ),
            $this->query->goToNextPage('8'),
             'trying to go to next page to last, getting last page instead');
    }

    /**
     * Tests goToPreviousPage
     * @depends testGoToFirstPage
     */
    public function testGoToPreviousPage()
    {
        $this->assertSame(
            array(
                array('ascii' => 'GHI', 'image' => '6', 'original' => 'ghi'),
                array('ascii' => 'KLM', 'image' => '7', 'original' => 'klm'),
            ),
            $this->query->goToPreviousPage('7'),
             'going to previous page');

        /**********/

        $this->assertSame(
            array(
                array('ascii' => 'ABC', 'image' => '1', 'original' => 'abc'),
                array('ascii' => 'ADE', 'image' => '2', 'original' => 'ade'),
            ),
            $this->query->goToPreviousPage('1'),
             'trying to go to prvious page to first, getting first page instead');
    }

    /**
     * Tests searchWord
     * @depends testGoToFirstPage
     */
    public function testSearchWord()
    {
        $this->assertSame(
            array(
                array('ascii' => 'ABC', 'image' => '1', 'original' => 'abc', 'previous' => ''),
                array('ascii' => 'ADE', 'image' => '2', 'original' => 'ade', 'previous' => 'ABC'),
            ),
            $this->query->searchWord('ABC'),
             'search first word');

        /**********/

        $this->assertSame(
            array(
                array('ascii' => 'ABC', 'image' => '1', 'original' => 'abc', 'previous' => ''),
                array('ascii' => 'ADE', 'image' => '2', 'original' => 'ade', 'previous' => 'ABC'),
            ),
            $this->query->searchWord('A'),
             'search before first word');

        /**********/

        $this->assertSame(
            array(
                array('ascii' => 'GHI', 'image' => '6', 'original' => 'ghi', 'previous' => 'DEF'),
                array('ascii' => 'KLM', 'image' => '7', 'original' => 'klm', 'previous' => 'GHI'),
            ),
            $this->query->searchWord('GHI'),
             'search first word of a page');

        /**********/

        $this->assertSame(
            array(
                array('ascii' => 'KLM', 'image' => '7', 'original' => 'klm', 'previous' => 'GHI'),
                array('ascii' => 'OPQ', 'image' => '8', 'original' => 'opq', 'previous' => 'KLM'),
            ),
            $this->query->searchWord('KLM'),
             'search word within a page');

        /**********/

        $this->assertSame(
            array(
                array('ascii' => 'KLM', 'image' => '7', 'original' => 'klm', 'previous' => 'GHI'),
                array('ascii' => 'OPQ', 'image' => '8', 'original' => 'opq', 'previous' => 'KLM'),
            ),
            $this->query->searchWord('KLM'),
             'search word within a page');

        /**********/

        $this->assertSame(
            array(
                array('ascii' => 'DEF', 'image' => '3', 'original' => 'def', 'previous' => 'ADE'),
                array('ascii' => 'DEF', 'image' => '4', 'original' => 'def', 'previous' => 'DEF'),
            ),
            $this->query->searchWord('G'),
             'search word within a page, get first occurence of top word on multiple pages');

        /**********/

        $this->assertSame(
            array(
                array('ascii' => 'OPQ', 'image' => '8', 'original' => 'opq'),
            ),
            $this->query->searchWord('Z'),
             'search word after lasst word');
    }
}

class Model_Query_InternalExtended extends Model_Query_Internal
{
}