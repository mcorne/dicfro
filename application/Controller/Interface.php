<?php

/**
 * Dicfro
 *
 * PHP 5
 *
 * @category  DicFro
 * @package   Controller
 * @author    Michel Corne <mcorne@yahoo.com>
 * @copyright 2008-2013 Michel Corne
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
        'about'        => 'information',
        'archives'     => 'information',
        'dictionaries' => 'information',
        'dictlist'     => true,
        'home'         => 'information',
        'introduction' => true,
        'next'         => true,
        'options'      => 'information',
        'page'         => true,
        'previous'     => true,
        'search'       => true,
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
     * Method overload to call an action
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

        return new $class($this->front->config['data-dir'], $this->dictionary['search']['properties'], $this->dictionary['query']);
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
     * Finds a dictionary by its URL
     *
     * @param string $url the dictionary URL
     * @return array the dictionary configuration
     */
    public function findDictionary($url)
    {
        $dictionaries = $this->front->config['dictionaries'];

        foreach($dictionaries as $id => $dictionary) {
            if ($id == $url or isset($dictionary['url']) and $dictionary['url'] == $url) {
                // the dictionary is found by its ID or URL
                $found = $id;
                break;
            }
        }

        if (! isset($found)) {
            // defaults dictionary
            $found = 'gdf';
        }

        $dictionary = $dictionaries[$found];
        $dictionary['id'] = $found;

        return $dictionary;
    }

    /**
     * Sets the view data after processing an action
     *
     * @return void
     */
    public function finish()
    {
        $this->view->dictionary = $this->dictionary;

        $this->view->homeLink = $this->setActionLink('home');
        $this->view->introductionLink = $this->setActionLink('introduction', $this->dictionary['url']);
        $this->view->optionsLink = $this->setActionLink('options');
        $this->view->aboutLink = $this->setActionLink('about');
        $this->view->dictionariesLink = $this->setActionLink('dictionaries');
        $this->view->dictlistLink = $this->setActionLink('dictlist');

        $this->view->wordLink = $this->setActionLink('search', $this->dictionary['url'], '%s');

        if ($this->parseActions() == 'introduction') {
            $action = 'introduction';
        } else {
            $action = 'search';
        }
        $this->view->dictionaryLink = $this->setActionLink($action, '%s', $this->view->word);

        if (isset($this->view->page)) {
            $this->view->previousPageLink = $this->setActionLink('previous', $this->dictionary['url'],
                $this->view->page, $this->view->volume, $this->view->word);
            $this->view->nextPageLink = $this->setActionLink('next', $this->dictionary['url'],
                $this->view->page, $this->view->volume, $this->view->word);

            if (empty($this->dictionary['volume'])) {
                $needVolume = '';
            } else {
                $needVolume = '%s';
            }

            // go to page link template used by js
            $this->view->goPageLink = $this->setActionLink('page', $this->dictionary['url'], '%s', $needVolume, $this->view->word);
        }

        $this->view->autoSearch = !empty($_COOKIE['auto-search']);
        $this->view->newTab = !empty($_COOKIE['new-tab']);

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
        $this->setDictionary();
        $this->setWord();
        $this->setPage();
        $this->setVolume();
        $this->setLanguage();
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
     * Sets an action link
     *
     * @return string the action link
     */
    public function setActionLink()
    {
        $arguments = func_get_args();
        $arguments = array_filter($arguments);
        $link = implode('/', $arguments);

        return $link;
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
     * Validates and sets the dictionary
     *
     * @return void
     * @see Model_Parser::setDictionary()
     */
    public function setDictionary()
    {
        $url = array_shift($this->front->actionParams);
        $this->dictionary = $this->findDictionary($url);

        if (! isset($this->dictionary['type'])) {
            // ghostwords for example is not meant to be called via HTTP
            throw new Exception('invalid dictionary');
        }

        if (! isset($this->dictionary['query'])) {
            $this->dictionary['query'] = array();
        }

        if (! isset($this->dictionary['search'])) {
            $this->dictionary['search'] = array();
        } else if (is_string($this->dictionary['search'])) {
            $this->dictionary['search'] = array('properties' => array('url' => $this->dictionary['search']));
        }

        if (! isset($this->dictionary['search']['properties']['dictionary'])) {
            $this->dictionary['search']['properties']['dictionary'] = $this->dictionary['id'];
        }

        if ($this->dictionary['type'] == 'internal' and empty($this->dictionary['introduction'])) {
            $this->dictionary['introduction'] = "{$this->dictionary['id']}.phtml";
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

        if (isset($_COOKIE['language'])) {
            // the language is set in a cookie
            $this->view->language = $_COOKIE['language'];

        } else {
            // the language is unknown, attempts to detect language
            $this->view->language = $language->detectLanguage();
        }

        $this->view->languages = $language->languages;

        // note: cannot set cookie in debug mode because PHPUnit already sent headers
        // expires as per interface.js setcookie() default
        defined('PHPUnit_MAIN_METHOD') or setrawcookie('language', $this->view->language, time() + 365 * 24 * 3600, '/' . $this->front->config['domain-subpath']);
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
        // note: cannot set cookie in debug mode because PHPUnit already sent headers
        defined('PHPUnit_MAIN_METHOD') or setrawcookie($cookie, $word, 0, '/' . $this->front->config['domain-subpath']);

        // sets specific language cookie
        $cookie .=  '-' . $this->dictionary['language'];
        // note: cannot set cookie in debug mode because PHPUnit already sent headers
        defined('PHPUnit_MAIN_METHOD') or setrawcookie($cookie, $word, 0, '/' . $this->front->config['domain-subpath']);
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
                $this->view->page = $param;
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
                $this->view->volume = $param;
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
        $this->view->word = strip_tags($word);
    }
}