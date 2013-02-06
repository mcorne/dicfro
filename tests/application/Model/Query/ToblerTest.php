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

require_once 'Model/Query/Tobler.php';

/**
 * Tobler Query class tests
 *
 * @category   Application
 * @package    DicFro
 * @subpackage Tests
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2013 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class ToblerTest extends PHPUnit_Framework_TestCase
{
    /**
     * The Query class instance
     * @var Model_Query_Tobler
     */
    public $query;

    /**
     * Prepares a test
     */
    protected function setUp()
    {
        Test::createTempDir();
        $this->query = new Model_Query_Tobler(Test::getTempDir());

        @mkdir(Test::getTempDir() . '/tobler');
        $this->setDatabase();
    }

    public function setDatabase()
    {
        $pdo = new PDO($this->query->dsn);

        $pdo->query('DROP TABLE IF EXISTS word');
        $pdo->query('CREATE TABLE word (ascii TEXT, line INTEGER)');
        $pdo->query('CREATE INDEX ascii ON word (ascii ASC)');
        $pdo->query('CREATE INDEX word_line ON word (line ASC)');

        $pdo->query('INSERT INTO word (ascii, line) VALUES ("AB",  "1")');
        $pdo->query('INSERT INTO word (ascii, line) VALUES ("AC",  "2")');
        $pdo->query('INSERT INTO word (ascii, line) VALUES ("AD",  "2")');
        $pdo->query('INSERT INTO word (ascii, line) VALUES ("AD",  "3")');

        $pdo->query('DROP TABLE IF EXISTS entry');
        $pdo->query('CREATE TABLE entry (lemma TEXT, line INTEGER, main TEXT, pof TEXT, variants TEXT)');
        $pdo->query('CREATE INDEX entry_line ON entry (line ASC)');

        $pdo->query('INSERT INTO entry (lemma, line, main, pof, variants) VALUES ("ab",    "1", "a", "b", "c")');
        $pdo->query('INSERT INTO entry (lemma, line, main, pof, variants) VALUES ("ac ad", "2", "g", "h", "i")');
        $pdo->query('INSERT INTO entry (lemma, line, main, pof, variants) VALUES ("ad",    "3", "d", "e", "f")');
    }

    /**
     * Tests searchWords
     */
    public function testSearchWords()
    {
        $this->assertSame(
            array(
                array('main' => 'd', 'variants' => 'f', 'pof' => 'e', 'lemma' => 'ad'),
                array('main' => 'g', 'variants' => 'i', 'pof' => 'h', 'lemma' => 'ac ad'),
            ),
            $this->query->searchWords('ad'),
             'searching words');


        /**********/

        $this->assertSame(
            array(),
            $this->query->searchWords('xyz'),
             'finding no words');
    }
}