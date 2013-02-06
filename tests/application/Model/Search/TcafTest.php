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

require_once 'Model/Search/Tcaf.php';

/**
 * Tcaf Search class tests
 *
 * @category   Application
 * @package    DicFro
 * @subpackage Tests
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2013 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class TcafSearchTest extends PHPUnit_Framework_TestCase
{
    /**
     * The Search class instance
     * @var Model_Search_Tcaf
     */
    public $search;

    /**
     * Prepares a test
     */
    protected function setUp()
    {
        Test::createTempDir();
        $this->search = new Model_Search_Tcaf(Test::getTempDir());
        $this->setDatabase();
    }

    public function setDatabase()
    {
        @mkdir(Test::getTempDir() . '/tcaf');
        $pdo = new PDO($this->search->query->dsn);

        $pdo->query('DROP TABLE IF EXISTS word');
        $pdo->query('CREATE TABLE word (ascii TEXT, composed INTEGER, infinitive TEXT, infinitive_ascii TEXT,
                                        original TEXT,  person TEXT, tense TEXT)');
        $pdo->query('CREATE INDEX word_ascii ON word (ascii ASC);');
        $pdo->query('CREATE INDEX infinitive_ascii ON word (infinitive_ascii ASC)');

        $pdo->query('INSERT INTO word (ascii, composed, infinitive, infinitive_ascii, original, person, tense)
                               VALUES ("AB",  "0",      "�",        "A",              "ab",     "1",    "nocomp")');
        $pdo->query('INSERT INTO word (ascii, composed, infinitive, infinitive_ascii, original, person, tense)
                               VALUES ("AE",  "0",      "�",        "A",              "ae",     "",    "comp.")');

        $pdo->query('DROP TABLE IF EXISTS entry');
        $pdo->query('CREATE TABLE entry (ascii TEXT, conjugation TEXT, original TEXT, tense TEXT)');
        $pdo->query('CREATE INDEX entry_ascii ON entry (ascii ASC)');

        $pdo->query('INSERT INTO entry (ascii, conjugation,  original, tense)
                               VALUES ("A",    "1 ab; 2 ac", "�",      "nocomp")');
    }

    /**
     * Tests searchWord
     */
    public function testSearchWord()
    {
        $this->assertSame(
            array(
                array(array(
                    'tense' => 'nocomp',
                    'conjugation' => '1 ab; 2 ac',
                    'original' => '�',
                )),
                array(array(
                    'original' => 'ae',
                    'infinitive' => '�',
                    'composed' => '0',
                )),
            ),
            $this->search->searchWord('ab'),
             'searching existing verb');

        /**********/

        $this->assertSame(
            array(
                array(array(
                    'tense' => 'nocomp',
                    'conjugation' => '1 ab; 2 ac',
                    'original' => '�',
                )),
                array(),
            ),
            $this->search->searchWord('ae'),
             'searching model verb');
    }
}