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

require_once 'Model/Query/Generic.php';
require_once 'Model/Search/Generic.php';

/**
 * Generic Search class tests
 *
 * @category   Application
 * @package    DicFro
 * @subpackage Tests
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2013 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class GenericSearchTest extends PHPUnit_Framework_TestCase
{
    /**
     * The Search class instance
     * @var Model_Search_GenericExtended
     */
    public $search;

    /**
     * Prepares a test
     */
    protected function setUp()
    {
        Test::createTempDir();
        $this->search = new Model_Search_GenericExtended(Test::getTempDir());

        $this->setErrataFiles();
        $this->setGhostwords();
        $this->setTcaf();
        $this->setTobler();
        $this->setWhitaker();
        $this->setDictionary();
    }

    public function setErrataFiles()
    {
        // creates errata directory
        $errataDirectory = Test::getTempDir() . '/dictionary/errata/02-2/';
        @mkdir($errataDirectory, null, true);
        // creates errata files
        touch($errataDirectory . 'abc.gif');
        touch($errataDirectory . 'def.gif');

        // creates errata directory
        $errataDirectory = Test::getTempDir() . '/dictionary/errata/02-3/';
        @mkdir($errataDirectory, null, true);
        // creates errata files
        touch($errataDirectory . 'ghi.gif');
        touch($errataDirectory . 'jkl.gif');

        $this->search->errataFiles = Test::getTempDir() . '/dictionary/errata/%s-%s/*.gif';
    }

    public function setGhostwords()
    {
        $database = Test::getTempDir() . '/ghostwords/dictionary.sqlite';
        @mkdir(dirname($database));
        $pdo = new PDO('sqlite:' . $database);

        $pdo->query('DROP TABLE IF EXISTS word');
        $pdo->query('CREATE TABLE word (ascii TEXT, original TEXT)');
        $pdo->query('CREATE INDEX ascii ON word (ascii ASC)');
        $pdo->query('INSERT INTO word (ascii, original) VALUES ("ABC", "abc")');
        $pdo->query('INSERT INTO word (ascii, original) VALUES ("DEF", "def")');
    }

    public function setTcaf()
    {
        $database = Test::getTempDir() . '/tcaf/dictionary.sqlite';
        @mkdir(dirname($database));
        $pdo = new PDO('sqlite:' . $database);

        $pdo->query('DROP TABLE IF EXISTS word');
        $pdo->query('CREATE TABLE word (ascii TEXT, composed INTEGER, infinitive TEXT, infinitive_ascii TEXT,
                                        original TEXT,  person TEXT, tense TEXT)');
        $pdo->query('CREATE INDEX word_ascii ON word (ascii ASC);');
        $pdo->query('CREATE INDEX infinitive_ascii ON word (infinitive_ascii ASC)');

        $pdo->query('INSERT INTO word (ascii, composed, infinitive, infinitive_ascii, original, person, tense)
                               VALUES ("ABC",  "0",      "�",        "A",              "abc",     "1",    "nocomp")');

        $pdo->query('DROP TABLE IF EXISTS entry');
        $pdo->query('CREATE TABLE entry (ascii TEXT, conjugation TEXT, original TEXT, tense TEXT)');
        $pdo->query('CREATE INDEX entry_ascii ON entry (ascii ASC)');

        $pdo->query('INSERT INTO entry (ascii, conjugation,  original, tense)
                               VALUES ("A",    "1 ab; 2 ac", "�",      "nocomp")');
    }

    public function setTobler()
    {
        $database = Test::getTempDir() . '/tobler/dictionary.sqlite';
        @mkdir(dirname($database));
        $pdo = new PDO('sqlite:' . $database);

        $pdo->query('DROP TABLE IF EXISTS word');
        $pdo->query('CREATE TABLE word (ascii TEXT, line INTEGER)');
        $pdo->query('CREATE INDEX ascii ON word (ascii ASC)');
        $pdo->query('CREATE INDEX word_line ON word (line ASC)');

        $pdo->query('INSERT INTO word (ascii, line) VALUES ("ABC",  "1")');

        $pdo->query('DROP TABLE IF EXISTS entry');
        $pdo->query('CREATE TABLE entry (lemma TEXT, line INTEGER, main TEXT, pof TEXT, variants TEXT)');
        $pdo->query('CREATE INDEX entry_line ON entry (line ASC)');

        $pdo->query('INSERT INTO entry (lemma, line, main, pof, variants) VALUES ("abc",    "1", "a", "b", "c")');
    }

    public function setWhitaker()
    {
        $database = Test::getTempDir() . '/whitaker/dictionary.sqlite';
        @mkdir(dirname($database));
        $pdo = new PDO('sqlite:' . $database);

        $pdo->query('DROP TABLE IF EXISTS word');
        $pdo->query('CREATE TABLE word (latin TEXT, line INTEGER)');
        $pdo->query('CREATE INDEX latin ON word (latin ASC)');
        $pdo->query('CREATE INDEX word_line ON word (line ASC)');

        $pdo->query('INSERT INTO word (latin, line) VALUES ("ABC",  "1")');

        $pdo->query('DROP TABLE IF EXISTS entry');
        $pdo->query('CREATE TABLE entry (info TEXT, line INTEGER, frequency TEXT)');
        $pdo->query('CREATE INDEX entry_line ON entry (line ASC)');

        $pdo->query('INSERT INTO entry (info, line, frequency) VALUES ("abc",    "1", "a")');
    }

    public function setDictionary()
    {
        @mkdir(Test::getTempDir() . '/test');
        $pdo = new PDO($this->search->query->dsn);
        $this->search->query->extraColumns = ', errata';

        $pdo->query('DROP TABLE IF EXISTS word');
        $pdo->query('CREATE TABLE word (ascii TEXT, image TEXT, original TEXT, previous TEXT, errata TEXT)');
        $pdo->query('CREATE INDEX ascii ON word (ascii ASC)');
        $pdo->query('CREATE INDEX image ON word (image ASC)');

        $pdo->query('INSERT INTO word (ascii, image, original, previous, errata) VALUES ("A",   "0200001", "a", "", "1")');
        $pdo->query('INSERT INTO word (ascii, image, original, previous, errata) VALUES ("ABC", "0200002", "abc", "A", "2")');
        $pdo->query('INSERT INTO word (ascii, image, original, previous, errata) VALUES ("DEF", "0200003", "def", "ADE", "3")');
        $pdo->query('INSERT INTO word (ascii, image, original, previous, errata) VALUES ("DEF", "0200004", "def", "DEF", "4")');
    }

    public function setSearchFlags()
    {
        $this->search->needErrataText = true;
        $this->search->needErrataImages = true;
        $this->search->needGhostwords = true;
        $this->search->needTobler = true;
        $this->search->needWhitaker = true;
        $this->search->needTcaf = true;
    }

   /**
     * Tests extractVolumeAndPage
     */
    public function testExtractVolumeAndPage()
    {
        $this->assertSame(
            array('12', '3456'),
            $this->search->extractVolumeAndPage('1203456'),
             'extracting volume and page with digit set to 0');

        /**********/

        $this->assertSame(
            array('12', '3456'),
            $this->search->extractVolumeAndPage('1213456'),
             'extracting volume and page with digit set to 1');

        /**********/

        $this->assertSame(
            array(),
            $this->search->extractVolumeAndPage('xyz'),
             'not extracting volume and page due to bad format');
    }

    /**
     * Tests setImageNumber
     */
    public function testSetImageNumber()
    {
        $this->assertSame(
            '1203456',
            $this->search->setImageNumber('12', '3456'),
             'setting image number');

        /**********/

        $this->search->digit = 1;
        $this->assertSame(
            '1213456',
            $this->search->setImageNumber('12', '3456'),
             'setting image number with digit set');
    }

    /**
     * Tests setImagePath
     */
    public function testSetImagePath()
    {
        $this->assertSame(
            'dictionary/test/mImg/abc.gif',
            $this->search->setImagePath(array('image' => 'abc')),
             'setting image path with default path');

        /**********/

        $this->search->imagePath = 'path/%s.gif';

        $this->assertSame(
            'path/abc.gif',
            $this->search->setImagePath(array('image' => 'abc')),
             'setting image path with specific path');
    }

    /**
     * Tests searchErrata
     */
    public function testSearchErrata()
    {
        $this->assertSame(
            array(
                Test::getTempDir() . '/dictionary/errata/02-3/ghi.gif',
                Test::getTempDir() . '/dictionary/errata/02-3/jkl.gif',
            ),
            $this->search->searchErrata('0200003'),
             'getting errata files');

        /**********/

        $this->assertSame(
            array(),
            $this->search->searchErrata('0909999'),
             'getting no errata file');
    }

    /**
     * Tests searchGhostwords
     */
    public function testSearchGhostwords()
    {
        $this->assertSame(
            array('abc', 'def'),
            $this->search->searchGhostwords(array('ascii' => 'ABC'), array('ascii' => '')),
             'searching ghostwords');

        /**********/

        $this->assertSame(
            array(),
            $this->search->searchGhostwords(array('ascii' => 'XYZ'), array('ascii' => '')),
             'finding no ghostword');
    }

    /**
     * Tests searchTcaf
     */
    public function testSearchTcaf()
    {
        $this->assertSame(
            array(
                array(
                    'original' => 'abc',
                    'infinitive' => '�',
                    'tense' => 'nocomp',
                    'person' => '1',
                ),
            ),
            $this->search->searchTcaf('abc'),
             'searching verbs');

        /**********/

        $this->assertSame(
            array(),
            $this->search->searchTcaf('xyz'),
             'finding no verb');
    }

    /**
     * Tests searchTobler
     */
    public function testSearchTobler()
    {
        $this->assertSame(
            array(
                array(
                    'main' => 'a',
                    'variants' => 'c',
                    'pof' => 'b',
                    'lemma' => 'abc',
                ),
            ),
            $this->search->searchTobler('abc'),
             'searching words in tobler');

        /**********/

        $this->assertSame(
            array(),
            $this->search->searchTobler('xyz'),
             'finding no word in tobler');
    }

    /**
     * Tests searchWhitaker
     */
    public function testSearchWhitaker()
    {
        $this->assertSame(
            array('abc'),
            $this->search->searchWhitaker('abc'),
             'searching words in whitaker');

        /**********/

        $this->assertSame(
            array(),
            $this->search->searchWhitaker('xyz'),
             'finding no word in whitaker');
    }

    /**
     * Tests updateResult
     */
    public function testUpdateResult()
    {
        $this->assertSame(
            array(
                'dictionary/test/mImg/0200003.gif',
                null,
                null,
                null,
                null,
                null,
                null,
                2,
                3,
                'abc',
            ),
            $this->search->updateResult(
                array(
                    array('image' => '0200003', 'original' => 'abc'),
                    array(),
                )
           ),
             'updating result with no search');

        /**********/

        $this->setSearchFlags();

        $this->assertSame(
            array(
                'dictionary/test/mImg/0200003.gif',
                array(
                    Test::getTempDir() . '/dictionary/errata/02-3/ghi.gif',
                    Test::getTempDir() . '/dictionary/errata/02-3/jkl.gif',
                ),
                'def',
                array('abc', 'def'),
                null,
                null,
                null,
                2,
                3,
                'abc',
            ),
            $this->search->updateResult(
                array(
                    array('image' => '0200003', 'original' => 'abc', 'errata' => 'def', 'ascii' => 'ABC'),
                    array('ascii' => ''),
                )
           ),
             'updating result with all search but no word');

        /**********/

        $this->setSearchFlags();

        $this->assertSame(
            array(
                'dictionary/test/mImg/0200003.gif',
                array(
                    Test::getTempDir() . '/dictionary/errata/02-3/ghi.gif',
                    Test::getTempDir() . '/dictionary/errata/02-3/jkl.gif',
                ),
                'def',
                array('abc', 'def'),
                array(array(
                    'main' => 'a',
                    'variants' => 'c',
                    'pof' => 'b',
                    'lemma' => 'abc',
                )),
                array(array(
                    'original' => 'abc',
                    'infinitive' => '�',
                    'tense' => 'nocomp',
                    'person' => '1',
                )),
                array('abc'),
                2,
                3,
                'abc',
            ),
            $this->search->updateResult(
                array(
                    array('image' => '0200003', 'original' => 'abc', 'errata' => 'def', 'ascii' => 'ABC'),
                    array('ascii' => ''),
                ),
                'abc'
           ),
             'updating result with everything');
    }

    /**
     * Tests goToNextPage
     */
    public function testGoToNextPage()
    {
        $this->setSearchFlags();

        $this->assertSame(
            array(
                'dictionary/test/mImg/0200002.gif',
                array(
                    Test::getTempDir() . '/dictionary/errata/02-2/abc.gif',
                    Test::getTempDir() . '/dictionary/errata/02-2/def.gif',
                ),
                '2',
                array('abc'),
                null,
                null,
                null,
                2,
                2,
                'abc',
            ),
            $this->search->goToNextPage('2', '1'),
             'going to next page');
    }

    /**
     * Tests goToPage
     */
    public function testGoToPage()
    {
        $this->setSearchFlags();

        $this->assertSame(
            array(
                'dictionary/test/mImg/0200003.gif',
                array(
                    Test::getTempDir() . '/dictionary/errata/02-3/ghi.gif',
                    Test::getTempDir() . '/dictionary/errata/02-3/jkl.gif',
                ),
                '3',
                array(),
                null,
                null,
                null,
                2,
                3,
                'def',
            ),
            $this->search->goToPage('2', '3'),
             'going to a page');
    }

    /**
     * Tests goToPreviousPage
     */
    public function testGoToPreviousPage()
    {
        $this->setSearchFlags();

        $this->assertSame(
            array(
                'dictionary/test/mImg/0200001.gif',
                array(),
                '1',
                array(),
                null,
                null,
                null,
                2,
                1,
                'a',
            ),
            $this->search->goToPreviousPage('2', '2'),
             'going to previous page');
    }

    /**
     * Tests searchWord
     */
    public function testSearchWord()
    {
        $this->setSearchFlags();

        $this->assertSame(
            array(
                'dictionary/test/mImg/0200002.gif',
                array(
                    Test::getTempDir() . '/dictionary/errata/02-2/abc.gif',
                    Test::getTempDir() . '/dictionary/errata/02-2/def.gif',
                ),
                '2',
                array('abc'),
                array(array(
                    'main' => 'a',
                    'variants' => 'c',
                    'pof' => 'b',
                    'lemma' => 'abc',
                )),
                array(array(
                    'original' => 'abc',
                    'infinitive' => '�',
                    'tense' => 'nocomp',
                    'person' => '1',
                )),
                array('abc'),
                2,
                2,
                'abc',
            ),
            $this->search->searchWord('abc'),
             'search word');
    }
}

class Model_Search_GenericExtended extends Model_Search_Generic
{
    public $dictionary = 'test';

    public function __construct($directory)
    {
        $this->directory = $directory;
        $this->query = new Model_QueryExtendedbis($directory);
    }
}

class Model_QueryExtendedbis extends Model_Query_Generic
{
    public function __construct($directory = '.')
    {
        parent::__construct($directory . '/test');
    }
}