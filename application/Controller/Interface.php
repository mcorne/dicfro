<?php

/**
 * Dicfro
 *
 * PHP 5
 *
 * @category  DicFro
 * @package   Controller
 * @author    Michel Corne <mcorne@yahoo.com>
 * @copyright 2008-2015 Michel Corne
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

require_once 'Base/View.php';
require_once 'Controller/Front.php';
require_once 'Model/Language.php';
// note: dictionary classes are included dynamically, see $dictionaries and createSearchObject()

/**
 * Interface controller
 *
 * @category  DicFro
 * @package   Controller
 * @author    Michel Corne <mcorne@yahoo.com>
 * @copyright 2008-2013 Michel Corne
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class Controller_Interface
{
    /**
     * List of actions
     * @var array
     */
    public $actions = array(
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
    );

    /**
     * Information on a dictionary
     * @var array
     */
    public $dictionary;

    /**
     * Front controller
     * @var object
     */
    public $front;

    /**
     * View object
     * @var object
     */
    public $view;

    /**
     * Constructor
     *
     * @param  object $front the front controller
     * @return void
     */
    public function __construct(Controller_Front $front)
    {
        $this->front = $front;
        $this->view = $this->front->view;
    }

    /**
     * Calls an action
     *
     * @param  string $action    the action name
     * @param  array  $arguments the list of arguments
     * @return void
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
     * Creates the a dictionary search object
     *
     * @return object the dictionary search object
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

        return new $class(
                $this->front->config['data-dir'],
                $this->dictionary['search']['properties'],
                $this->dictionary['query'],
                $this->front->config['dictionary-dir']);
    }

    /**
     * Sets the URLs to check the dictionaries availability
     */
    public function dictionariesAvailabilityAction()
    {
        $dictionaries = [];

        foreach($this->front->config['dictionaries'] as $id => $dictionary) {
            if (! isset($dictionary['availability-test'])) {
                $urls = null;

            } elseif ($dictionary['type'] == 'internal') {
                $dictionaryUrl = isset($dictionary['url']) ? $dictionary['url'] : $id;
                $urls = sprintf('http://www.micmap.org/dicfro/search/%s/%s', $dictionaryUrl, $dictionary['availability-test']);

            } elseif (! isset($dictionary['search'])) {
                $urls = null; // to process if there happens to be such a case one day

            } elseif (is_string($dictionary['search'])) {
                $urls = $dictionary['search'] . $dictionary['availability-test'];

            } elseif (! isset($dictionary['search']['properties']['url'])) {
                $urls = null; // to process if there happens to be such a case one day

            } elseif (is_string($dictionary['search']['properties']['url'])) {
                $urls = $dictionary['search']['properties']['url'] . $dictionary['availability-test'];

                if (isset($dictionary['search']['properties']['suffix'])) {
                    $urls .= $dictionary['search']['properties']['suffix'];
                }

            } else {
                $urls = [];

                foreach ($dictionary['search']['properties']['url'] as $index => $url) {
                    if (isset($dictionary['availability-test'][$index])) {
                        $urls[$index] = sprintf($url, $dictionary['availability-test'][$index]);
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
    }

    /**
     * Tests and validates dictionaries search
     */
    public function dictionariesSearchTestAction()
    {
        $result = [];

        foreach($this->front->config['dictionaries'] as $id => $dictionary) {
            $this->dictionary = $dictionary;
            $this->setDictionaryDefaults($id);
            $search = $this->createSearchObject();
            $result[] = $search->searchWord('a');
        }

        $this->view->result =  $result;
        $this->view->information = "information/dictionaries-search-test.phtml";
    }

    /**
     * Processes the action to display the dictionary list English page
     */
    public function dictlistAction()
    {
        $this->view->information = "information/dictionaries.phtml";
        $this->view->englishDictList = true;
    }

    /**
     * Sets the view data after processing an action
     *
     * @return void
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
     * Processes the action to display a dictionary "introduction" page
     *
     * @return void
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

    /**
     * Sets data before processing an action
     *
     * @return void
     */
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
     * Processes the "go to next page" action
     *
     * @return void
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

        $this->view->information = "information/options.phtml";
    }

    /**
     * Processes the "go to a page" action
     *
     * @return void
     */
    public function pageAction($action = 'goToPage')
    {
        if ($this->dictionary['type'] != 'external') {
            $search = $this->createSearchObject();
            $result = $search->$action($this->view->volume, $this->view->page);
            $this->view->assign($result);
        }
    }

    /**
     * Parses an action
     *
     * Note: the name of this method must not finish with "Action"
     * so it is not mistaken with a proper action
     *
     * @return string the action parsed
     */
    public function parseActions()
    {
        return str_replace('Action', '', $this->front->action);
    }

    /**
     * Processes the "go to previous page" action
     *
     * @return void
     */
    public function previousAction()
    {
        $this->pageAction('goToPreviousPage');
    }

    /**
     * Processes the "search for a word" action
     *
     * @return void
     */
    public function searchAction()
    {
        $search = $this->createSearchObject();
        $result = $search->searchWord($this->view->word);
        $this->view->assign($result);
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
     * Sets a cookie
     *
     * @param string $name
     * @param mixed $value
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

    /**
     * Validates and sets the dictionary
     *
     * @return void
     */
    public function setDictionary()
    {
        // attempts to extract the dictionary from the url
        $dictionary = array_shift($this->front->actionParams);

        if (empty($dictionary)) {
            // attempts to extract the dictionary posted by a form, eg "options"
            $dictionary = $this->front->getPost('dictionary');
        }

        if (empty($dictionary) and ! empty($this->front->params['default-dictionary'])) {
            if ($this->front->params['default-dictionary'] == 'last-used-dictionary') {
                $dictionary = $this->front->params['dictionary'];
            } else {
                $dictionary = $this->front->params['default-dictionary'];
            }
        }

        if (! empty($dictionary)) {
            $dictionary = $this->validateDictionary($dictionary);
        }

        if (empty($dictionary)) {
            // invalid or missing dictionary, eg "home", defaults to the lagnauge default dictionary
            $dictionary = $this->front->config['dictionary-defaults'][$this->view->language];
        }

        $this->setcookie('dictionary', $dictionary , 30);
        $this->dictionary = $this->front->config['dictionaries'][$dictionary];
        $this->setDictionaryDefaults($dictionary);
    }

    /**
     * Sets the dictionary defaults
     *
     * @return void
     * @see Model_Parser::setDictionary()
     */
    public function setDictionaryDefaults($dictionary)
    {
        $this->dictionary['id'] = $dictionary;

        if (! isset($this->dictionary['type'])) {
            // ghostwords for example is not meant to be called via HTTP
            throw new Exception('invalid dictionary');
        }

        if (! isset($this->dictionary['query'])) {
            $this->dictionary['query'] = array();
        }

        if (isset($this->dictionary['volume'])) {
            $this->dictionary['query']['properties']['useVolume'] = $this->dictionary['volume'] == 'input';
        }

        if (! isset($this->dictionary['search'])) {
            $this->dictionary['search'] = array();

        } else if (is_string($this->dictionary['search'])) {
            if ($this->dictionary['type'] == 'internal') {
                $this->dictionary['search'] = array('properties' => array('imagePath' => $this->dictionary['search']));
            } else {
                $this->dictionary['search'] = array('properties' => array('url' => $this->dictionary['search']));
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

    /**
     * Sets the interface language
     */
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

    /**
     * Sets last word in cookies
     */
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

    /**
     * Validates and sets the entry hash
     *
     * @return void
     */
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

    /**
     * Validates and sets the dictionary page
     *
     * @return void
     */
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

    /**
     * Validates and sets the dictionary volume
     *
     * @return void
     */
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

    /**
     * Validates and sets the word to search for
     *
     * @return void
     */
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

    /**
     * Validates the dictionary
     *
     * @param string $dictionary
     * @return string
     */
    public function validateDictionary($dictionary)
    {
        foreach($this->front->config['dictionaries'] as $id => $info) {
            if ($id == $dictionary or isset($info['url']) and $info['url'] == $dictionary) {
                // the dictionary is found by its ID or URL
                return $id;
            }
        }

        return false;
    }
}