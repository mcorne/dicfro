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

require_once 'Model/Query/Ghostwords.php';

/**
 * Ghostwords Query class tests
 *
 * @category   Application
 * @package    DicFro
 * @subpackage Tests
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2013 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class GhostwordsTest extends PHPUnit_Framework_TestCase
{
    /**
     * The Query class instance
     * @var Model_Query_Ghostwords
     */
    public $query;

    /**
     * Prepares a test
     */
    protected function setUp()
    {
        Test::createTempDir();
        $this->query = new Model_Query_Ghostwords(Test::getTempDir());

        @mkdir(Test::getTempDir() . '/ghostwords');
        $this->setDatabase();
    }

    public function setDatabase()
    {
        $pdo = new PDO($this->query->dsn);
        $pdo->query('DROP TABLE IF EXISTS word');
        $pdo->query('CREATE TABLE word (ascii TEXT, original TEXT)');
        $pdo->query('CREATE INDEX ascii ON word (ascii ASC)');
        $pdo->query('INSERT INTO word (ascii, original) VALUES ("ABC", "abc")');
        $pdo->query('INSERT INTO word (ascii, original) VALUES ("DEF", "def")');
        $pdo->query('INSERT INTO word (ascii, original) VALUES ("GHI", "ghi")');
        $pdo->query('INSERT INTO word (ascii, original) VALUES ("KLM", "klm")');
        $pdo->query('INSERT INTO word (ascii, original) VALUES ("OPQ", "opq")');
    }

    /**
     * Tests searchWordsBetween
     */
    public function testSearchWordsBetween()
    {
        $this->assertSame(
            array(
                'def',
                'ghi',
                'klm',
            ),
            $this->query->searchWordsBetween('DEF', 'OPQ'),
             'searching between 2 words');

        /**********/

        $this->assertSame(
            array(
                'abc',
                'def',
                'ghi',
                'klm',
                'opq',
            ),
            $this->query->searchWordsBetween('A', 'Z'),
             'finding all words');

        /**********/

        $this->assertSame(
            array(),
            $this->query->searchWordsBetween('GHIx', 'KLM'),
             'finding no words');
    }

    /**
     * Tests searchWordsFrom
     */
    public function testSearchWordsFrom()
    {
        $this->assertSame(
            array(
                'def',
                'ghi',
                'klm',
                'opq',
            ),
            $this->query->searchWordsFrom('DEF'),
             'searching from a word');

        /**********/

        $this->assertSame(
            array(
                'abc',
                'def',
                'ghi',
                'klm',
                'opq',
            ),
            $this->query->searchWordsFrom('A'),
             'finding all words');

        /**********/

        $this->assertSame(
            array(),
            $this->query->searchWordsFrom('Z'),
             'finding no words');
    }

    /**
     * Tests searchWords
     * @depends testSearchWordsBetween
     * @depends testSearchWordsFrom
     */
    public function testSearchWords()
    {
        $this->assertSame(
            array(
                'def',
                'ghi',
                'klm',
            ),
            $this->query->searchWords('DEF', 'OPQ'),
             'searching between 2 words');

        /**********/

        $this->assertSame(
            array(
                'def',
                'ghi',
                'klm',
                'opq',
            ),
            $this->query->searchWords('DEF', null),
             'searching from a word');
    }
}