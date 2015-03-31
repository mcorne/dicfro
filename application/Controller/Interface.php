<?php
/**
 * Dicfro
 *
 * @author    Michel Corne <mcorne@yahoo.com>
 * @copyright 2008-2015 Michel Corne
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

require_once 'Base/View.php';
require_once 'Controller/Front.php';
require_once 'Model/Language.php';
require_once 'Model/Query.php';

/**
 * Interface controller
 *
 * Note: dictionary classes are included dynamically, see $dictionaries and createSearchObject()
 */
class Controller_Interface
{
    public $actions = [
        'about'                     => 'information',
        'archives'                  => 'information',
        'dictionaries'              => 'information',
        'dictionaries-availability' => true,
        'dictionaries-search-test'  => true,
        'dictlist'                  => true,
        'home'                      => 'information',
        'introduction'              => true,
        'next'                      => true,
        'options'                   => 'information',
        'page'                      => true,
        'previous'                  => true,
        'search'                    => true,
        'unit-test'                 => true,
    ];

    public $dictionary;
    public $front; // Controller_Front
    public $view; // Base_View

    /**
     * @param Controller_Front $front
     */
    public function __construct(Controller_Front $front)
    {
        $this->front = $front;
        $this->view = $this->front->view;

        Model_Query::$debug = !empty($this->front->params['debug']);
    }

    /**
     * @param string $action
     * @param array $arguments
     */
    public function __call($action, $arguments)
    {
        $action = $this->setActions();

        if ($this->actions[$action] === 'information') {
            $this->view->information = "information/$action.phtml";

        } else {
            $this->{$this->front->action}();
        }
    }

    /**
     * @return Model_Search
     * @see Model_Parser::createSearchObject()
     */
    public function createSearchObject()
    {
        if (isset($this->dictionary['search']['class'])) {
            $class = $this->dictionary['search']['class'];
        } else {
            $class = 'Model_Search_' . ucfirst($this->dictionary['type']);
        }

        $file = str_replace('_', '/', $class) . '.php';
        require_once $file;

        return new $class($this->dictionary['search']['properties'], $this->dictionary['query'], $this->front->config['dictionary-dir']);
    }

    /**
     * Sets the URLs to check the dictionaries availability
     */
    public function dictionariesAvailabilityAction()
    {
        $testCases = require __DIR__ . '/../tests/cases/availability-tests.php';
        $dictionaries = [];

        foreach($this->front->config['dictionaries'] as $dictionaryId => $dictionary) {
            if (! isset($testCases[$dictionaryId])) {
                $urls = null;

            } elseif ($dictionary['type'] == 'internal') {
                $dictionaryUrl = isset($dictionary['url']) ? $dictionary['url'] : $dictionaryId;
                $urls = sprintf('http://www.micmap.org/dicfro/search/%s/%s', $dictionaryUrl, $testCases[$dictionaryId]);

            } elseif (! isset($dictionary['search'])) {
                $urls = null; // to process if there happens to be such a case one day

            } elseif (is_string($dictionary['search'])) {
                $urls = $dictionary['search'] . $testCases[$dictionaryId];

            } elseif (! isset($dictionary['search']['properties']['url'])) {
                $urls = null; // to process if there happens to be such a case one day

            } elseif (is_string($dictionary['search']['properties']['url'])) {
                $urls = $dictionary['search']['properties']['url'] . $testCases[$dictionaryId];

                if (isset($dictionary['search']['properties']['suffix'])) {
                    $urls .= $dictionary['search']['properties']['suffix'];
                }

            } else {
                $urls = [];

                foreach ($dictionary['search']['properties']['url'] as $index => $url) {
                    if (isset($testCases[$dictionaryId][$index])) {
                        $urls[$index] = sprintf($url, $testCases[$dictionaryId][$index]);
                    } else {
                        $urls[$index] = null;
                    }
                }
            }

            $dictionaries[] = [
                'name' => $dictionary['name'],
                'urls' => $urls,
            ];
        }

        $this->view->dictionaries = $dictionaries;
        $this->view->information = "information/dictionaries-availability.phtml";
        $this->view->noImage = true;
    }

    public function dictionariesPageTestAction()
    {
        $currentDictionary = $this->dictionary;
        $testCases = require __DIR__ . '/../tests/cases/page-tests.php';
        $results = [];

        foreach($this->front->config['dictionaries'] as $dictionaryId => $dictionary) {
            if ($dictionary['type'] == 'external') {
                continue;
            }

            $this->dictionary = $dictionary;
            $this->setDictionaryDefaults($dictionaryId);
            $search = $this->createSearchObject();

            if (! isset($testCases[$dictionaryId])) {
                $result = null;

            } else {
                $result = [];

                foreach ($testCases[$dictionaryId] as $testCase) {
                    if (isset($testCase['volume'])) {
                        $testCase['comment'] = sprintf('%s / %s', $testCase['page'], $testCase['volume']);
                        $volume = $testCase['volume'];
                    } else {
                        $testCase['comment'] = $testCase['page'];
                        $volume = 0;
                    }

                    $method = $testCase['method'];
                    $testCase['result'] = $search->$method($volume, $testCase['page']);
                    $result[] = $testCase;
                }
            }

            $filename = sprintf('%s/../tests/results/page-tests/%s.php', __DIR__, $dictionaryId);
            $expected = $this->readExpectedTestResults($filename);

            if (! $expected and $result) {
                $this->writeTestResult($filename, $result);
            }

            $results[] = [
                'basename' => $dictionaryId,
                'expected' => $expected,
                'name'     => $dictionary['name'],
                'result'   => $result,
            ];
        }

        $this->dictionary = $currentDictionary;

        $this->view->information = "information/test-results.phtml";
        $this->view->noImage = true;
        $this->view->results = $results;
        $this->view->testDirectory = 'tests/results/page-tests';
        $this->view->title = 'Dictionaries Page Test';
    }

    public function dictionariesSearchTestAction()
    {
        $currentDictionary = $this->dictionary;
        $testCases = require __DIR__ . '/../tests/cases/search-tests.php';
        $results = [];

        foreach($this->front->config['dictionaries'] as $dictionaryId => $dictionary) {
            $this->dictionary = $dictionary;
            $this->setDictionaryDefaults($dictionaryId);
            $search = $this->createSearchObject();

            if (! isset($testCases[$dictionaryId])) {
                $result = null;

            } elseif (is_string($testCases[$dictionaryId])) {
                $result = [[
                    'comment' => $testCases[$dictionaryId],
                    'result'  => $search->searchWord($testCases[$dictionaryId]),
                ]];

            } else {
                $result = [];

                foreach (array_keys($dictionary['search']['properties']['url']) as $index) {
                    if (isset($testCases[$dictionaryId][$index])) {
                        $result[$index] = [
                            'comment' => $testCases[$dictionaryId][$index],
                            'result'  => $search->searchWord($testCases[$dictionaryId][$index]),
                        ];
                    } else {
                        $result[$index] = null;
                    }
                }
            }

            $filename = sprintf('%s/../tests/results/search-tests/%s.php', __DIR__, $dictionaryId);
            $expected = $this->readExpectedTestResults($filename);

            if (! $expected and $result) {
                $this->writeTestResult($filename, $result);
            }

            $results[] = [
                'basename' => $dictionaryId,
                'expected' => $expected,
                'name'     => $dictionary['name'],
                'result'   => $result,
            ];
        }

        $this->dictionary = $currentDictionary;

        $this->view->information = "information/test-results.phtml";
        $this->view->noImage = true;
        $this->view->results = $results;
        $this->view->testDirectory = 'tests/results/search-tests';
        $this->view->title = 'Dictionaries Search Test';
    }

    /**
     * Displays the dictionary list English page
     */
    public function dictlistAction()
    {
        $this->view->information = "information/dictionaries.phtml";
        $this->view->englishDictList = true;
    }

    /**
     * Sets the view data after processing an action
     */
    public function finish()
    {
        $this->view->dictionary = $this->dictionary;
        $this->view->previousAction = $this->parseActions();

        if ($this->view->previousAction == 'introduction') {
            $this->view->action = 'introduction';
        } else {
            $this->view->action = 'search';
        }

        $this->view->params = $this->front->params;
        $this->view->isIE = $this->isIE();
        $this->view->domainSubpath = $this->front->config['domain-subpath'];

        $this->setLastWordCookies();
    }

    /**
     * Displays a dictionary "introduction" page
     */
    public function introductionAction()
    {
        if (! isset($this->dictionary['introduction'])) {
            $this->homeAction();

        } else if (strpos($this->dictionary['introduction'], 'http') !== false) {
            $this->view->externalDict = $this->dictionary['introduction'];

        } else {
            $this->view->introduction = 'introduction/' . $this->dictionary['introduction'];
        }
    }

    public function init()
    {
        $this->setLanguage();
        $this->setDictionary();
        $this->setWord();
        $this->setPage();
        $this->setVolume();
        $this->setEntryHash();
    }

    public function isIE()
    {
        return stripos($_SERVER['HTTP_USER_AGENT'], 'msie');
    }

    /**
     * Goes to the dictionary next page
     */
    public function nextAction()
    {
        $this->pageAction('goToNextPage');
    }

    public function optionsAction()
    {
        if (($value = $this->front->getPost('language')) !== null) {
            $this->setcookie('language', $value, 30);
            $this->setLanguage();
        }

        if (($value = $this->front->getPost('default-dictionary')) !== null) {
            $this->setcookie('default-dictionary', $value, 30);
        }

        if (($value = $this->front->getPost('no-auto-search')) !== null) {
            $this->setcookie('no-auto-search', $value, 30);
        }

        if (($value = $this->front->getPost('open-dict-in-new-tab')) !== null) {
            $this->setcookie('open-dict-in-new-tab', $value, 30);
        }

        if (($value = $this->front->getPost('debug')) !== null) {
            $this->setcookie('debug', $value, 30);
        }

        $this->view->information = "information/options.phtml";
    }

    /**
     * Goes to the dictionary page
     */
    public function pageAction($action = 'goToPage')
    {
        if ($this->dictionary['type'] != 'external') {
            $search = $this->createSearchObject();
            $result = $search->$action($this->view->volume, $this->view->page);
            $this->view->assign($result);

            if (Model_Query::$debug) {
                $volume = $this->view->volume === '' ? 'null' : $this->view->volume;
                $action = sprintf('%s::%s(%s, %s)', get_class($search), $action, $volume, $this->view->page);

                $this->view->actionTrace = [
                    'action' => $action,
                    'result' => $result,
                ];

                $this->view->queryTrace = Model_Query::$trace;
            }
        }
    }

    /**
     * string type
     */
    public function parseActions()
    {
        return str_replace('Action', '', $this->front->action);
    }

    /**
     * Goes to the dictionary previous page
     */
    public function previousAction()
    {
        $this->pageAction('goToPreviousPage');
    }

    /**
     * @param string $filename
     * @return array
     */
    public function readExpectedTestResults($filename)
    {
        if (file_exists($filename)) {
            $expected = include $filename;
        } else {
            $expected = null;
        }

        return $expected;
    }

    /**
     * Searches for a word" in the dictionary
     */
    public function searchAction()
    {
        $search = $this->createSearchObject();
        $result = $search->searchWord($this->view->word);
        $this->view->assign($result);

        if (Model_Query::$debug) {
            $this->view->actionTrace = [
                'action' => sprintf('%s::searchWord()', get_class($search)),
                'result' => $result,
            ];

            $this->view->queryTrace = Model_Query::$trace;
        }

    }

    /**
     * Validates and sets the action
     *
     * Note: the name of this method must not finish with "Action"
     * so it is not mistaken with a proper action
     *
     * @return string the validated action or the "home" action if invalid
     */
    public function setActions()
    {
        $action = $this->parseActions();

        if (! isset($this->actions[$action])) {
            $action = 'home';
        }

        return $action;
    }

    /**
     *
     * @param string $name
     * @param string|int $value
     * @param int $days
     */
    public function setcookie($name, $value, $days = 0)
    {
        if (! defined('PHPUnit_MAIN_METHOD')) {
            if (empty($days)) {
                $expire = 0;
            } else {
                $expire = time() + $days * 24 * 3600;
            }

            setcookie($name, $value , $expire, '/' . $this->front->config['domain-subpath']);
        }
    }

    public function setDictionary()
    {
        // attempts to extract the dictionary from the url
        $dictionaryId = array_shift($this->front->actionParams);

        if (empty($dictionaryId)) {
            // attempts to extract the dictionary posted by a form, eg "options"
            $dictionaryId = $this->front->getPost('dictionary');
        }

        if (empty($dictionaryId) and ! empty($this->front->params['default-dictionary'])) {
            if ($this->front->params['default-dictionary'] == 'last-used-dictionary') {
                $dictionaryId = $this->front->params['dictionary'];
            } else {
                $dictionaryId = $this->front->params['default-dictionary'];
            }
        }

        if (! empty($dictionaryId)) {
            $dictionaryId = $this->validateDictionary($dictionaryId);
        }

        if (empty($dictionaryId)) {
            // invalid or missing dictionary, eg "home", defaults to the lagnauge default dictionary
            $dictionaryId = $this->front->config['dictionary-defaults'][$this->view->language];
        }

        $this->setcookie('dictionary', $dictionaryId , 30);
        $this->dictionary = $this->front->config['dictionaries'][$dictionaryId];
        $this->setDictionaryDefaults($dictionaryId);
    }

    public function setDictionaryDefaults($dictionaryId)
    {
        $this->dictionary['id'] = $dictionaryId;

        if (! isset($this->dictionary['type'])) {
            // ghostwords for example is not meant to be called via HTTP
            throw new Exception('invalid dictionary');
        }

        if (! isset($this->dictionary['query'])) {
            $this->dictionary['query'] = null;
        }

        if (isset($this->dictionary['volume'])) {
            $this->dictionary['query']['properties']['useVolume'] = $this->dictionary['volume'] == 'input';
        }

        if (! isset($this->dictionary['search'])) {
            $this->dictionary['search'] = null;

        } else if (is_string($this->dictionary['search'])) {
            if ($this->dictionary['type'] == 'internal') {
                $this->dictionary['search'] = ['properties' => ['imagePath' => $this->dictionary['search']]];
            } else {
                $this->dictionary['search'] = ['properties' => ['url' => $this->dictionary['search']]];
            }
        }

        if (! isset($this->dictionary['search']['properties']['dictionary'])) {
            $this->dictionary['search']['properties']['dictionary'] = $this->dictionary['id'];
        }

        if ($this->dictionary['type'] != 'external' and empty($this->dictionary['introduction'])) {
            $this->dictionary['introduction'] = "{$this->dictionary['id']}.phtml";
        }

        if (empty($this->dictionary['image'])) {
            $this->dictionary['image'] = "{$this->dictionary['id']}.jpg";
        }

        if (empty($this->dictionary['url'])) {
            $this->dictionary['url'] = $this->dictionary['id'];
        }
    }

    public function setLanguage()
    {
        $language = new Language();

        if (! empty($this->front->params['language'])) {
            // the language is set in a cookie
            $this->view->language = $this->front->params['language'];

        } else {
            // the language is unknown, attempts to detect language
            $this->view->language = $language->detectLanguage();
        }

        $this->view->languages = $language->languages;

        $this->setcookie('language', $this->view->language, 30);
    }

    public function setLastWordCookies()
    {
        // escapes cookie delimiters
        $word = str_replace('=', '%3D', $this->view->word);
        $word = str_replace(';', '%3B', $word);

        // sets multilanguage cookie
        $cookie = 'last-word';
        $this->setcookie($cookie, $word);

        // sets specific language cookie
        $cookie .=  '-' . $this->dictionary['language'];
        $this->setcookie($cookie, $word);
    }

    public function setEntryHash()
    {
        foreach($this->front->actionParams as $idx => $param) {
            if (is_numeric($param)) {
                $this->view->entryHash = $param;
                unset($this->front->actionParams[$idx]);
                break;
            }
        }
    }

    public function setPage()
    {
        foreach($this->front->actionParams as $idx => $param) {
            if (is_numeric($param)) {
                // converts page number to integer for clarity
                $this->view->page = (int) $param;
                unset($this->front->actionParams[$idx]);
                break;
            }
        }
    }

    public function setVolume()
    {
        foreach($this->front->actionParams as $idx => $param) {
            if (is_numeric($param)) {
                // converts volume number to integer for clarity
                $this->view->volume = (int) $param;
                unset($this->front->actionParams[$idx]);
                break;
            }
        }
    }

    public function setWord()
    {
        $word = '';

        foreach($this->front->actionParams as $idx => $param) {
            if (!is_numeric($param)) {
                $word = $param;
                unset($this->front->actionParams[$idx]);
                break;
            }
        }

        $word = urldecode($word);
        // filters out HTML special characters for security
        $this->view->word = strtr($word, '<>&"', '    ');
    }

    public function unitTestsAction()
    {
        $testFilenames = array_merge(
            glob(__DIR__ . '/../tests/cases/unit-tests/*.php'),
            glob(__DIR__ . '/../tests/cases/unit-tests/*/*.php')
        );

        foreach ($testFilenames as $testFilename) {
            require_once $testFilename;
            $classname = basename($testFilename, '.php');
            $unitTest = new $classname();
            $result = $unitTest->run();

            $resultsFilename = str_replace('/tests/cases/', '/tests/results/', $testFilename);
            @mkdir(dirname($resultsFilename), 0777, true);
            $expected = $this->readExpectedTestResults($resultsFilename);

            if (! $expected and $result) {
                $this->writeTestResult($resultsFilename, $result);
            }

            $results[] = [
                'basename' => $classname,
                'expected' => $expected,
                'name'     => $classname,
                'result'   => $result,
            ];
        }

        $this->view->information = "information/test-results.phtml";
        $this->view->noImage = true;
        $this->view->results = $results;
        $this->view->testDirectory = 'tests/unit-tests/...';
        $this->view->title = 'Unit Tests';
    }

    /**
     * @param string $dictionaryId
     * @return boolean
     */
    public function validateDictionary($dictionaryId)
    {
        foreach($this->front->config['dictionaries'] as $id => $info) {
            if ($id == $dictionaryId or isset($info['url']) and $info['url'] == $dictionaryId) {
                // the dictionary is found by its ID or URL
                return $id;
            }
        }

        return false;
    }

    /**
     * @param string $filename
     * @param array $result
     * @throws Exception
     */
    public function writeTestResult($filename, $result)
    {
        $php = var_export($result, true);
        $content = sprintf("<?php\nreturn %s;", $php);

        if (! file_put_contents($filename, $content)) {
            throw new Exception("cannot write: $filename");
        }
    }
}