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

require_once 'Test.php';

require_once 'Model/Query/Whitaker.php';

/**
 * Whitaker Query class tests
 *
 * @category   Application
 * @package    DicFro
 * @subpackage Tests
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2010 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class WhitakerTest extends PHPUnit_Framework_TestCase
{
    /**
     * The Query class instance
     * @var object
     */
    public $query;

    /**
     * Prepares a test
     */
    protected function setUp()
    {
        Test::createTempDir();
        $this->query = new Model_Query_Whitaker(Test::getTempDir());

        @mkdir(Test::getTempDir() . '/whitaker');
        $this->setDatabase();
    }

    public function setDatabase()
    {
        $pdo = new PDO($this->query->dsn);

        $pdo->query('DROP TABLE IF EXISTS word');
        $pdo->query('CREATE TABLE word (latin TEXT, line INTEGER)');
        $pdo->query('CREATE INDEX latin ON word (latin ASC)');
        $pdo->query('CREATE INDEX word_line ON word (line ASC)');

        $pdo->query('INSERT INTO word (latin, line) VALUES ("AB",  "1")');
        $pdo->query('INSERT INTO word (latin, line) VALUES ("AC",  "2")');
        $pdo->query('INSERT INTO word (latin, line) VALUES ("AD",  "2")');
        $pdo->query('INSERT INTO word (latin, line) VALUES ("AD",  "3")');

        $pdo->query('DROP TABLE IF EXISTS entry');
        $pdo->query('CREATE TABLE entry (info TEXT, line INTEGER, frequency TEXT)');
        $pdo->query('CREATE INDEX entry_line ON entry (line ASC)');

        $pdo->query('INSERT INTO entry (info, line, frequency) VALUES ("ab",    "1", "a")');
        $pdo->query('INSERT INTO entry (info, line, frequency) VALUES ("ac ad", "2", "g")');
        $pdo->query('INSERT INTO entry (info, line, frequency) VALUES ("ad",    "3", "d")');
    }

    /**
     * Tests searchWords
     */
    public function testSearchWords()
    {
        $this->assertSame(
            array(
                'ad',
                'ac ad',
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