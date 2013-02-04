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

require_once 'Controller/Front.php';
require_once 'Base/View.php';
require_once 'Controller/Interface.php';

/**
 * Interface controller class tests
 *
 * @category   Application
 * @package    DicFro
 * @subpackage Tests
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2010 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class InterfaceTest extends PHPUnit_Framework_TestCase
{
    /**
     * The Interface class instance
     * @var Controller_Interface
     */
    public $interface;

    /**
     * Prepares a test
     */
    protected function setUp()
    {
        Test::createTempDir();
        $front = $this->setEngine();

        $this->interface = new Controller_Interface($front);
    }

    public function setEngine()
    {
        $config = array('data-dir' => Test::getTempDir());
        $view = new Base_View($config, false);

        return new Controller_Front($config, $view);
    }

    public function setChretienDatabase()
    {
        $dir = Test::getTempDir() . '/chretien';
        is_dir($dir) or mkdir($dir);
        require_once 'Model/Search/Generic.php';
        $search = new Model_Search_Generic(Test::getTempDir(), array('dictionary' => 'chretien'));
        $pdo = new PDO($search->query->dsn);

        $pdo->query('DROP TABLE IF EXISTS word');
        $pdo->query('CREATE TABLE word (ascii TEXT, image TEXT, original TEXT, previous TEXT)');
        $pdo->query('CREATE INDEX ascii ON word (ascii ASC)');
        $pdo->query('CREATE INDEX image ON word (image ASC)');

        $pdo->query('INSERT INTO word (ascii, image, original, previous) VALUES ("A",   "0000001", "a", "")');
        $pdo->query('INSERT INTO word (ascii, image, original, previous) VALUES ("ABC", "0000002", "abc", "a")');
        $pdo->query('INSERT INTO word (ascii, image, original, previous) VALUES ("DEF", "0000003", "def", "abc")');
    }

    public function setTcafDatabase()
    {
        $dir = Test::getTempDir() . '/tcaf';
        is_dir($dir) or mkdir($dir);
        require_once 'Model/Search/Tcaf.php';
        $search = new Model_Search_Tcaf(Test::getTempDir(), array('dictionary' => 'tcaf'));
        $pdo = new PDO($search->query->dsn);

        $pdo->query('DROP TABLE IF EXISTS word');
        $pdo->query('CREATE TABLE word (ascii TEXT, composed INTEGER, infinitive TEXT, infinitive_ascii TEXT,
                                        original TEXT,  person TEXT, tense TEXT)');
        $pdo->query('CREATE INDEX word_ascii ON word (ascii ASC);');
        $pdo->query('CREATE INDEX infinitive_ascii ON word (infinitive_ascii ASC)');

        $pdo->query('INSERT INTO word (ascii, composed, infinitive, infinitive_ascii, original, person, tense)
                               VALUES ("AB",  "0",      "À",        "A",              "ab",     "1",    "nocomp")');

        $pdo->query('DROP TABLE IF EXISTS entry');
        $pdo->query('CREATE TABLE entry (ascii TEXT, conjugation TEXT, original TEXT, tense TEXT)');
        $pdo->query('CREATE INDEX entry_ascii ON entry (ascii ASC)');

        $pdo->query('INSERT INTO entry (ascii, conjugation,  original, tense)
                               VALUES ("A",    "1 ab; 2 ac", "À",      "nocomp")');
    }

    /**
     * Tests aboutAction
     */
    public function testAboutAction()
    {
        $this->interface->aboutAction();

        $this->assertSame(
            array('information' => 'information/about.phtml'),
            $this->interface->view->toArray(),
            'processing about action');
    }

    /**
     * Tests completeActions
     */
    public function testCompleteActions()
    {
        $this->assertSame(
            'abcAction',
            $this->interface->completeActions('abc'),
            'completing action');
    }

    /**
     * Tests createSearchObject
     */
    public function testCreateSearchObject()
    {
        $this->assertInstanceOf(
            'Model_Search_Generic',
            $this->interface->createSearchObject(),
            'creating default search model');

        /**********/

        $this->dictionary['search']['class'] = 'Model_Search_Generic';

        $this->assertInstanceOf(
            'Model_Search_Generic',
            $this->interface->createSearchObject(),
            'creating generic search model');

        /**********/

        $this->interface->dictionary['search']['properties']['abc'] = 123;
        $object = $this->interface->createSearchObject();

        $this->assertSame(
            123,
            $object->abc,
            'passing properties');

        /**********/

        $this->interface->dictionary['query']['class'] = 'Model_Query_Generic';
        $object = $this->interface->createSearchObject();

        $this->assertInstanceOf(
            'Model_Query_Generic',
            $object->query,
            'passing query class');
    }

    /**
     * Tests translateActions
     */
    public function testTranslateActions()
    {
        $this->assertSame(
            'a-propos',
            $this->interface->translateActions('about'),
            'translating valid action');

        /**********/

        $this->assertSame(
            'xyz',
            $this->interface->translateActions('xyz'),
            'translating invalid action');
    }

    /**
     * Tests setActionLink
     *
     * @depends testTranslateActions
     */
    public function testSetActionLink()
    {
        $this->assertSame(
            'a-propos/abc/def',
            $this->interface->setActionLink('about', 'abc', 'def'),
            'setting link');

        /**********/

        $this->interface->view->noDictChange = true;

        $this->assertSame(
            'a-propos/abc/def?no-dict-change=1',
            $this->interface->setActionLink('about', 'abc', '', 'def'),
            'setting link with no dictionary change');
    }

    /**
     * Tests parseActions
     */
    public function testParseActions()
    {
        $this->interface->front->action = 'abcAction';

        $this->assertSame(
            'abc',
            $this->interface->parseActions(),
            'parsing action');
    }

    /**
     * Tests finish
     *
     * @depends testSetActionLink
     * @depends testParseActions
     */
    public function testFinish()
    {
        $_SERVER['HTTP_USER_AGENT'] = 'xyz';
        $this->interface->front->config['domain-subpath'] = 'uvw';

        $this->interface->dictionary = 'abc';
        $this->interface->view->word = 'def';
        $this->interface->view->page = '123';
        $this->interface->view->volume = '456';

        $this->interface->finish();

        $this->assertSame(
            array(
    'aboutLink' => 'a-propos',
    'autoSearch' => false,
    'dictionariesLink' => 'liste-des-dictionnaires',
    'dictionary' => 'abc',
    'dictionaryLink' => 'chercher/%s/def',
    'domainSubpath' => 'uvw',
    'goPageLink' => 'aller-page/a/%s/def',
    'helpLink' => 'aide',
    'homeLink' => 'accueil',
    'introductionLink' => 'introduction/a',
    'isIE' => false,
    'newTab' => false,
    'nextPageLink' => 'page-suivante/a/123/456/def',
    'optionsLink' => 'options',
    'page' => '123',
    'previousPageLink' => 'page-precedente/a/123/456/def',
    'volume' => '456',
    'word' => 'def',
    'wordLink' => 'chercher/a/%s',
            ),
            $this->interface->view->toArray(),
            'finishing processing');

        /**********/

        $this->interface->view->dictionary = 'dictionnaire-godefroy';
        $this->interface->front->action = 'introductionAction';

        $this->interface->finish();

        $this->assertSame(
            array(
    'aboutLink' => 'a-propos',
    'autoSearch' => false,
    'dictionariesLink' => 'liste-des-dictionnaires',
    'dictionary' => 'abc',
    'dictionaryLink' => 'introduction/%s/def',
    'domainSubpath' => 'uvw',
    'goPageLink' => 'aller-page/a/%s/def',
    'helpLink' => 'aide',
    'homeLink' => 'accueil',
    'introductionLink' => 'introduction/a',
    'isIE' => false,
    'newTab' => false,
    'nextPageLink' => 'page-suivante/a/123/456/def',
    'optionsLink' => 'options',
    'page' => '123',
    'previousPageLink' => 'page-precedente/a/123/456/def',
    'volume' => '456',
    'word' => 'def',
    'wordLink' => 'chercher/a/%s',
            ),
            $this->interface->view->toArray(),
            'finishing processing for godefroy');
    }

    /**
     * Tests flipActions
     */
    public function testFlipActions()
    {
        $this->interface->flipActions();

        $this->assertSame(
            array(
    'aPropos' => 'about',
    'listeDesDictionnaires' => 'dictionaries',
    'aide' => 'help',
    'accueil' => 'home',
    'introduction' => 'introduction',
    'pageSuivante' => 'next',
    'options' => 'options',
    'allerPage' => 'page',
    'pagePrecedente' => 'previous',
    'chercher' => 'search',
            ),
            $this->interface->actionsFlipped,
            'flipping actions');
    }

    /**
     * Tests helpAction
     */
    public function testHelpAction()
    {
        $this->interface->helpAction();

        $this->assertSame(
            array('information' => 'information/help.phtml'),
            $this->interface->view->toArray(),
            'processing help action');
    }

    /**
     * Tests homeAction
     */
    public function testHomeAction()
    {
        $this->interface->homeAction();

        $this->assertSame(
            array('information' => 'information/home.phtml'),
            $this->interface->view->toArray(),
            'processing home action');
    }

    /**
     * Tests isInternalDictionary
     */
    public function testIsInternalDictionary()
    {
        $this->interface->dictionary['internal'] = true;

        $this->assertTrue(
            $this->interface->isInternalDictionary(),
            'checking is internal dictionary');

        /**********/

        $this->interface->dictionary['internal'] = false;

        $this->assertFalse(
            $this->interface->isInternalDictionary(),
            'checking is external dictionary');
    }

    /**
     * Tests introductionAction
     */
    public function testIntroductionAction()
    {
        $this->interface->dictionary['internal'] = true;
        $this->interface->dictionary['introduction'] = 'abc';

        $this->interface->introductionAction();

        $this->assertSame(
            array('introduction' => 'introduction/abc'),
            $this->interface->view->toArray(),
            'processing introduction for internal dictionary');

        /**********/

        $this->interface->dictionary['internal'] = false;

        $this->interface->introductionAction();

        $this->assertSame(
            array('externalDict' => 'abc'),
            $this->interface->view->toArray(false, 'introduction'),
            'processing introduction for external dictionary');
    }

    /**
     * Tests setActions
     *
     * @depends testParseActions
     * @depends testCompleteActions
     */
    public function testSetActions()
    {
        $this->interface->front->action = 'aProposAction';

        $this->interface->flipActions();
        $this->interface->setActions();

        $this->assertSame(
            'aboutAction',
            $this->interface->front->action,
            'setting an action');

        /**********/

        $this->interface->front->action = 'xyz';

        $this->interface->setActions();

        $this->assertSame(
            'homeAction',
            $this->interface->front->action,
            'changing invalid action to home action');
    }

    /**
     * Tests __call
     *
     * @depends testSetActions
     */
    public function test__call()
    {
        $this->interface->flipActions();
        $this->interface->front->action = 'aProposAction';
        $this->interface->aProposAction();

        $this->assertSame(
            array('information' => 'information/about.phtml'),
            $this->interface->view->toArray(),
            'overloading action');
    }

    /**
     * Tests setDictionary
     */
    public function testSetDictionary()
    {
        $this->interface->front->actionParams = array('gaffiot');

        $this->interface->setDictionary();

        $this->assertSame(
            array(
                'gaffiot',
                array(
                    'class' => 'Model_Search_Gaffiot',
                    'introduction' => 'gaffiot.phtml',
                    'internal' => true,
                ),
            ),
            array(
                $this->interface->view->dictionary,
                $this->interface->dictionaryInfo,
            ),
            'setting dictionary');

        /**********/

        $this->interface->front->actionParams = array('xyz');

        $this->interface->setDictionary();

        $this->assertSame(
            array(
                'dictionnaire-godefroy',
                array(
                    'class' => 'Model_Search_Gdf',
                    'introduction' => 'gdf.phtml',
                    'internal' => true,
                ),
            ),
            array(
                $this->interface->view->dictionary,
                $this->interface->dictionaryInfo,
            ),
            'defaulting invalid dictionary');
    }

    /**
     * Tests setNoDictChange
     */
    public function testSetNoDictChange()
    {
        $this->interface->setNoDictChange();

        $this->assertFalse(
            $this->interface->view->noDictChange,
            'unsetting no dictionary change');

        /**********/

        $this->interface->front->params['no-dict-change'] = true;

        $this->interface->setNoDictChange();

        $this->assertTrue(
            $this->interface->view->noDictChange,
            'setting no dictionary change');
    }

    /**
     * Tests setPage
     */
    public function testSetPage()
    {
        $this->interface->front->actionParams = array('abc', 'def');

        $this->interface->setPage();

        $this->assertSame(
            array(
                false,
                array('abc', 'def'),
            ),
            array(
                isset($this->interface->view->page),
                $this->interface->front->actionParams,
            ),
            'setting no page');

        /**********/

        $this->interface->front->actionParams = array('abc', 123, 'def');

        $this->interface->setPage();

        $this->assertSame(
            array(
                123,
                array(0 => 'abc', 2 => 'def'),
            ),
            array(
                $this->interface->view->page,
                $this->interface->front->actionParams,
            ),
            'setting page');
    }

    /**
     * Tests setVolume
     */
    public function testSetVolume()
    {
        $this->interface->front->actionParams = array('abc', 'def');

        $this->interface->setVolume();

        $this->assertSame(
            array(
                false,
                array('abc', 'def'),
            ),
            array(
                isset($this->interface->view->volume),
                $this->interface->front->actionParams,
            ),
            'setting no volume');

        /**********/

        $this->interface->front->actionParams = array('abc', 123, 'def');

        $this->interface->setVolume();

        $this->assertSame(
            array(
                123,
                array(0 => 'abc', 2 => 'def'),
            ),
            array(
                $this->interface->view->volume,
                $this->interface->front->actionParams,
            ),
            'setting volume');
    }

    /**
     * Tests setWord
     */
    public function testSetWord()
    {
        $this->interface->front->actionParams = array(123, 456);

        $this->interface->setWord();

        $this->assertSame(
            array(
                '',
                array(123, 456),
            ),
            array(
                $this->interface->view->word,
                $this->interface->front->actionParams,
            ),
            'setting no word');

        /**********/

        $this->interface->front->actionParams = array(123, 'abc', 456);

        $this->interface->setWord();

        $this->assertSame(
            array(
                'abc',
                array(0 => 123, 2 => 456),
            ),
            array(
                $this->interface->view->word,
                $this->interface->front->actionParams,
            ),
            'setting word');

        /**********/

        $this->interface->front->actionParams = array(123, 'abc%20<xyz>def</xyz>', 456);

        $this->interface->setWord();

        $this->assertSame(
            array(
                'abc def',
                array(0 => 123, 2 => 456),
            ),
            array(
                $this->interface->view->word,
                $this->interface->front->actionParams,
            ),
            'setting and stripping word');
    }

    /**
     * Tests init
     *
     * @depends testFlipActions
     * @depends testSetDictionary
     * @depends testSetWord
     * @depends testSetPage
     * @depends testSetVolume
     * @depends testSetNoDictChange
     */
    public function testInit()
    {
        $this->interface->front->actionParams = array('dictionnaire-godefroy', '123', '456', 'abc', 'def');

        $this->interface->init();

        $this->assertSame(
            array(
                array(
                    'dictionary' => 'dictionnaire-godefroy',
                    'noDictChange' => false,
                    'page' => '123',
                    'volume' => '456',
                    'word' => 'abc',
                ),
                array(3 => 'def'),
            ),
            array(
                $this->interface->view->toArray(),
                $this->interface->front->actionParams,
            ),
            'initializing an action');
    }

    /**
     * Tests setViewElements
     */
    public function testSetViewElements()
    {
        $this->interface->view->dictionary = 'abc';
        $this->interface->setViewElements(array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j'));

        $this->assertSame(
            array(
    'definition' => 'a',
    'dictionary' => 'abc',
    'errataImages' => 'b',
    'errataText' => 'c',
    'firstWord' => 'j',
    'ghostwords' => 'd',
    'identifiedLatinWords' => 'g',
    'identifiedVerbs' => 'f',
    'identifiedWords' => 'e',
    'page' => 'i',
    'volume' => 'h',
            ),
            $this->interface->view->toArray(),
            'setting view element');;

        /**********/

        $this->interface->view->dictionary = 'lexique-roman';
        $this->interface->setViewElements(array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j'));

        $this->assertSame(
            array(
    'dictionary' => 'lexique-roman',
    'errataImages' => 'b',
    'errataText' => 'c',
    'firstWord' => 'j',
    'ghostwords' => 'd',
    'identifiedLatinWords' => 'g',
    'identifiedVerbs' => 'f',
    'identifiedWords' => 'e',
    'page' => 'i',
    'volume' => 'h',
            ),
            $this->interface->view->toArray(false, 'definition'),
            'setting view elements for lexique-roman');
    }

    /**
     * Tests pageAction
     *
     * @depends testIsInternalDictionary
     * @depends testCreateSearchObject
     * @depends testSetViewElements
     * @depends testSetDictionary
     */
    public function testPageAction()
    {
        $this->setChretienDatabase();
        $this->interface->front->actionParams = array('cnrtl');
        $this->interface->setDictionary();

        $this->interface->pageAction();

        $this->assertSame(
            array(
                'dictionary' => 'cnrtl',
            ),
            $this->interface->view->toArray(true),
            'going to page for external dictionary');

        /**********/

        $this->interface->setDictionary('glossaire-chretien-de-troyes');
        $this->interface->view->volume = 0;
        $this->interface->view->page = 2;

        $this->interface->pageAction();

        $this->assertSame(
            array(
                'definition' => 'dictionary/chretien/mImg/0000002.gif',
                'dictionary' => 'glossaire-chretien-de-troyes',
                'firstWord' => 'abc',
                'page' => 2,
            ),
            $this->interface->view->toArray(true),
            'going to page for internal dictionary');
    }

    /**
     * Tests nextAction
     *
     * @depends testPageAction
     */
    public function testNextAction()
    {
        $this->setChretienDatabase();

        $this->interface->setDictionary('glossaire-chretien-de-troyes');
        $this->interface->view->volume = 0;
        $this->interface->view->page = 1;

        $this->interface->nextAction();

        $this->assertSame(
            array(
                'definition' => 'dictionary/chretien/mImg/0000002.gif',
                'dictionary' => 'glossaire-chretien-de-troyes',
                'firstWord' => 'abc',
                'page' => 2,
            ),
            $this->interface->view->toArray(true),
            'going next page action');
    }

    /**
     * Tests previousAction
     *
     * @depends testPageAction
     */
    public function testPreviousAction()
    {
        $this->setChretienDatabase();

        $this->interface->setDictionary('glossaire-chretien-de-troyes');
        $this->interface->view->volume = 0;
        $this->interface->view->page = 3;

        $this->interface->previousAction();

        $this->assertSame(
            array(
                'definition' => 'dictionary/chretien/mImg/0000002.gif',
                'dictionary' => 'glossaire-chretien-de-troyes',
                'firstWord' => 'abc',
                'page' => 2,
            ),
            $this->interface->view->toArray(true),
            'going previous page action');
    }

    /**
     * Tests searchExternalDict
     *
     * @depends testCreateSearchObject
     */
    public function testSearchExternalDict()
    {
        $this->interface->view->word = 'œil';

        $this->interface->front->config['dictionaries']['cnrtl']['search'] = 'http://www.cnrtl.fr/definition/';
        $this->interface->front->actionParams = array('cnrtl');
        $this->interface->setDictionary('cnrtl');
        $this->interface->searchExternalDict();

        $this->assertSame(
            array(
                'externalDict' => 'http://www.cnrtl.fr/definition/œil',
                'word' => 'œil',
            ),
            $this->interface->view->toArray(true),
            'searching external dictionary');

        /**********/

        $this->interface->front->config['dictionaries']['whitaker']['search'] = 'http://lysy2.archives.nd.edu/cgi-bin/words.exe?';
        $this->interface->front->actionParams = array('whitaker');
        $this->interface->setDictionary('whitaker');
        $this->interface->searchExternalDict();

        $this->assertSame(
            array(
                'externalDict' => 'http://lysy2.archives.nd.edu/cgi-bin/words.exe?OEIL',
                'word' => 'œil',
            ),
            $this->interface->view->toArray(true),
            'searching ascii dictionary');

        /**********/

        $this->interface->front->config['dictionaries']['jeanneau']['search'] = array('class' => 'Model_Search_Jeanneau');
        $this->interface->front->actionParams = array('jeanneau');
        $this->interface->setDictionary('jeanneau');
        $this->interface->searchExternalDict();

        $this->assertSame(
            array(
                'externalDict' => 'http://www.prima-elementa.fr/Dico-o03.html#oeil',
                'word' => 'œil',
            ),
            $this->interface->view->toArray(true),
            'searching jeanneau dictionary');
    }

    /**
     * Tests searchInternalDict
     *
     * @depends testCreateSearchObject
     * @depends testSetViewElements
     */
    public function testSearchInternalDict()
    {
        $this->interface->view->word = 'abc';
        $this->interface->front->config['dictionaries']['chretien']['internal'] = true;
        $this->interface->front->actionParams = array('chretien');
        $this->setChretienDatabase();
        $this->interface->setDictionary('chretien');

        $this->interface->searchInternalDict();

        $this->assertSame(
            array(
                'definition' => 'dictionary/chretien/mImg/0000002.gif',
                'firstWord' => 'abc',
                'page' => 2,
                'word' => 'abc',
            ),
            $this->interface->view->toArray(true),
            'searching internal dictionary');

        /**********/

        $this->interface->view->word = 'ab';
        $this->interface->front->config['dictionaries']['tcaf']['internal'] = true;
        $this->interface->front->config['dictionaries']['tcaf']['search']['class'] = 'Model_Search_Tcaf';
        $this->interface->front->actionParams = array('tcaf');
        $this->setTcafDatabase();
        $this->interface->setDictionary('tcaf');

        $this->interface->searchInternalDict();

        $this->assertSame(
            array(
                'tcaf' => array(array(
                    'tense' => 'nocomp',
                    'conjugation' => '1 ab; 2 ac',
                    'original' => 'À',
                )),
                'word' => 'ab',
            ),
            $this->interface->view->toArray(true, array('firstWord', 'page', 'definition')),
            'searching tcaf');
    }
    /**
     * Tests searchAction
     *
     * @depends testIsInternalDictionary
     * @depends testSearchExternalDict
     * @depends testSearchInternalDict
     */
    public function testSearchAction()
    {
        $this->interface->view->word = 'abc';

        $this->interface->front->config['dictionaries']['chretien']['internal'] = true;
        $this->interface->front->actionParams = array('chretien');
        $this->setChretienDatabase();
        $this->interface->setDictionary('chretien');

        $this->interface->searchAction();

        $this->assertSame(
            array(
                'definition' => 'dictionary/chretien/mImg/0000002.gif',
                'firstWord' => 'abc',
                'page' => 2,
                'word' => 'abc',
            ),
            $this->interface->view->toArray(true),
            'searching internal dictionary');

        /**********/

        $this->interface->front->config['dictionaries']['cnrtl']['search'] = 'http://www.cnrtl.fr/definition/';
        $this->interface->front->actionParams = array('cnrtl');
        $this->interface->setDictionary('cnrtl');

        $this->interface->searchAction();

        $this->assertSame(
            array(
                'externalDict' => 'http://www.cnrtl.fr/definition/abc',
                'word' => 'abc',
            ),
            $this->interface->view->toArray(true, array('firstWord', 'page', 'definition')),
            'searching external dictionary');
    }
}