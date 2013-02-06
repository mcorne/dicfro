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

require_once 'Model/Query/Tcaf.php';

/**
 * Tcaf Query class tests
 *
 * @category   Application
 * @package    DicFro
 * @subpackage Tests
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2013 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class TcafTest extends PHPUnit_Framework_TestCase
{
    /**
     * The Query class instance
     * @var Model_Query_Tcaf
     */
    public $query;

    /**
     * Prepares a test
     */
    protected function setUp()
    {
        Test::createTempDir();
        $this->query = new Model_Query_Tcaf(Test::getTempDir());

        @mkdir(Test::getTempDir() . '/tcaf');
        $this->setDatabase();
    }

    public function setDatabase()
    {
        $pdo = new PDO($this->query->dsn);

        $pdo->query('DROP TABLE IF EXISTS word');
        $pdo->query('CREATE TABLE word (ascii TEXT, composed INTEGER, infinitive TEXT, infinitive_ascii TEXT,
                                        original TEXT,  person TEXT, tense TEXT)');
        $pdo->query('CREATE INDEX word_ascii ON word (ascii ASC);');
        $pdo->query('CREATE INDEX infinitive_ascii ON word (infinitive_ascii ASC)');

        $pdo->query('INSERT INTO word (ascii, composed, infinitive, infinitive_ascii, original, person, tense)
                               VALUES ("AB",  "0",      "�",        "A",              "ab",     "1",    "nocomp")');
        $pdo->query('INSERT INTO word (ascii, composed, infinitive, infinitive_ascii, original, person, tense)
                               VALUES ("AC",  "0",      "�",        "A",              "ac",     "2",    "nocomp")');
        $pdo->query('INSERT INTO word (ascii, composed, infinitive, infinitive_ascii, original, person, tense)
                               VALUES ("AD",  "1",      "�",        "A",              "ad",     "3",    "nocomp")');
        $pdo->query('INSERT INTO word (ascii, composed, infinitive, infinitive_ascii, original, person, tense)
                               VALUES ("AE",  "0",      "�",        "A",              "ae",     "",    "comp.")');
        $pdo->query('INSERT INTO word (ascii, composed, infinitive, infinitive_ascii, original, person, tense)
                               VALUES ("AF",  "1",      "�",        "A",              "af",     "",    "comp.")');

        $pdo->query('INSERT INTO word (ascii, composed, infinitive, infinitive_ascii, original, person, tense)
                               VALUES ("BB",  "0",      "B",        "B",              "bb",     "1",    "nocomp")');
        $pdo->query('INSERT INTO word (ascii, composed, infinitive, infinitive_ascii, original, person, tense)
                               VALUES ("BC",  "0",      "B",        "B",              "bc",     "2",    "nocomp")');
        $pdo->query('INSERT INTO word (ascii, composed, infinitive, infinitive_ascii, original, person, tense)
                               VALUES ("BD",  "1",      "B",        "B",              "bd",     "3",    "nocomp")');
        $pdo->query('INSERT INTO word (ascii, composed, infinitive, infinitive_ascii, original, person, tense)
                               VALUES ("BE",  "0",      "B",        "B",              "be",     "",    "comp.")');
        $pdo->query('INSERT INTO word (ascii, composed, infinitive, infinitive_ascii, original, person, tense)
                               VALUES ("BF",  "1",      "B",        "B",              "bf",     "",    "comp.")');

        $pdo->query('INSERT INTO word (ascii, composed, infinitive, infinitive_ascii, original, person, tense)
                               VALUES ("AB",  "0",      "C",        "C",              "cc",     "1",    "nocomp")');

        $pdo->query('DROP TABLE IF EXISTS entry');
        $pdo->query('CREATE TABLE entry (ascii TEXT, conjugation TEXT, original TEXT, tense TEXT)');
        $pdo->query('CREATE INDEX entry_ascii ON entry (ascii ASC)');

        $pdo->query('INSERT INTO entry (ascii, conjugation,  original, tense)
                               VALUES ("A",    "1 ab; 2 ac", "�",      "nocomp")');
        $pdo->query('INSERT INTO entry (ascii, conjugation,  original, tense)
                               VALUES ("B",    "1 bb; 2 bc", "B",      "nocomp")');
    }

    /**
     * Tests searchVerbConjugation
     */
    public function testSearchVerbConjugation()
    {
        $this->assertSame(
            array(
                array(
                    'tense' => 'nocomp',
                    'conjugation' => '1 ab; 2 ac',
                    'original' => '�',
                ),
            ),
            $this->query->searchVerbConjugation('ab'),
             'searching conjugations');

        /**********/

        $this->assertSame(
            array(),
            $this->query->searchVerbConjugation('xyz'),
             'finding no conjugation');
    }

    /**
     * Tests searchModelConjugation
     */
    public function testSearchModelConjugation()
    {
        $this->assertSame(
            array(
                array(
                    'tense' => 'nocomp',
                    'conjugation' => '1 ab; 2 ac',
                    'original' => '�',
                ),
            ),
            $this->query->searchModelConjugation('ae'),
             'searching model conjugations');

        /**********/

        $this->assertSame(
            array(),
            $this->query->searchModelConjugation('xyz'),
             'finding no model conjugation');
    }

    /**
     * Tests searchComposedVerbs
     */
    public function testSearchComposedVerbs()
    {
        $this->assertSame(
            array(
                array(
                    'original' => 'ae',
                    'infinitive' => '�',
                    'composed' => '0',
                ),
                array(
                    'original' => 'af',
                    'infinitive' => '�',
                    'composed' => '1',
                ),
            ),
            $this->query->searchComposedVerbs('ab'),
             'searching model conjugations');

        /**********/

        $this->assertSame(
            array(),
            $this->query->searchComposedVerbs('xyz'),
             'finding no model conjugation');
    }

    /**
     * Tests searchVerbs
     */
    public function testSearchVerbs()
    {
        $this->assertSame(
            array(
                array(
                    'original' => 'ab',
                    'infinitive' => '�',
                    'tense' => 'nocomp',
                    'person' => '1',
                ),
                array(
                    'original' => 'cc',
                    'infinitive' => 'C',
                    'tense' => 'nocomp',
                    'person' => '1',
                ),
            ),
            $this->query->searchVerbs('ab'),
             'searching verb forms');

        /**********/

        $this->assertSame(
            array(),
            $this->query->searchVerbs('xyz'),
             'finding no verb form');
    }
}