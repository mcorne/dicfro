<?php

/**
 * Dicfro
 *
 * PHP 5
 *
 * @category  DicFro
 * @package   Controller
 * @author    Michel Corne <mcorne@yahoo.com>
 * @copyright 2008-2012 Michel Corne
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

require_once 'Base/String.php';
require_once 'Base/View.php';
require_once 'Controller/Front.php';
// note: dictionary classes are included dynamically, see $dictionaries and createSearchObject()

/**
 * Interface controller
 *
 * @category  DicFro
 * @package   Controller
 * @author    Michel Corne <mcorne@yahoo.com>
 * @copyright 2008-2012 Michel Corne
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class Controller_Interface
{
    /**
     * List of actions
     * @var array
     */
    public $actions = array(
        'about'        => 'a-propos',
        'dictionaries' => 'liste-des-dictionnaires',
        'help'         => 'aide',
        'home'         => 'accueil',
        'introduction' => 'introduction',
        'next'         => 'page-suivante',
        'options'      => 'options',
        'page'         => 'aller-page',
        'previous'     => 'page-precedente',
        'search'       => 'chercher',
    );

    /**
     * Flipped list of actions
     * @var array
     */
    public $actionsFlipped;

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
        $method = $this->setActions();
        $this->$method();
    }

    /**
     * Processes the action to display the "about" page
     *
     * @return void
     */
    public function aboutAction()
    {
        $this->view->information = 'information/about.phtml';
    }

    /**
     * Complete an action method name
     *
     * Note: the name of this method must not finish with "Action"
     * so it is not mistaken with a proper action
     *
     * @param  string $action the name of the action
     * @return string the method name
     */
    public function completeActions($action)
    {
        return $action . 'Action';
    }

    /**
     * Creates the a dictionary search object
     *
     * @return object the dictionary search object
     */
    public function createSearchObject()
    {
        if (isset($this->dictionary['search']['class'])) {
            $class = $this->dictionary['search']['class'];
        } else {
            $class = 'Model_Search_Generic';
        }

        if (isset($this->dictionary['search']['properties'])) {
            $properties = $this->dictionary['search']['properties'];
        } else {
            $properties = array();
        }

        $file = str_replace('_', '/', $class) . '.php';
        require_once $file;

        if (isset($this->dictionary['query'])) {
            $query = $this->dictionary['query'];
        } else {
            $query = array();
        }

        return new $class($this->front->config['data-dir'], $properties, $query);
    }

    /**
     * Processes the action to display the "dictionaries" page
     *
     * @return void
     */
    public function dictionariesAction()
    {
        $this->view->information = 'information/dictionaries.phtml';
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
        $this->view->helpLink = $this->setActionLink('help');
        $this->view->aboutLink = $this->setActionLink('about');
        $this->view->dictionariesLink = $this->setActionLink('dictionaries');

        $this->view->wordLink = $this->setActionLink('search', $this->dictionary['url'], '%s');

        if ($this->parseActions() == 'introduction') {
            $action = 'introduction';
        } else {
            $action = 'search';
        }

        $this->view->dictionaryLink = $this->setActionLink($action, '%s', $this->view->word);

        $this->view->previousPageLink = $this->setActionLink('previous', $this->dictionary['url'],
            $this->view->page, $this->view->volume, $this->view->word);
        $this->view->nextPageLink = $this->setActionLink('next', $this->dictionary['url'],
            $this->view->page, $this->view->volume, $this->view->word);

        if (($this->dictionary['id'] == 'gdf' or $this->dictionary['id'] == 'gdfc')) {
            $needVolume = '%s';
        } else {
            $needVolume = '';
        }

        $this->view->goPageLink = $this->setActionLink('page', $this->dictionary['url'], '%s', $needVolume, $this->view->word);

        $this->view->autoSearch = !empty($_COOKIE['auto-search']);
        $this->view->newTab = !empty($_COOKIE['new-tab']);

        $this->view->isIE = $this->isIE();
        $this->view->domainSubpath = $this->front->config['domain-subpath'];

        $this->setLastWordCookies();
    }

    /**
     * Flips the dictionaries information
     *
     * Note: the name of this method must not finish with "Action"
     * so it is not mistaken with a proper action
     *
     * @return void
     */
    public function flipActions()
    {
        $string = new Base_String;

        $actions = array_map(array($string, 'dash2CamelCase'), $this->actions);
        $this->actionsFlipped = array_flip($actions);
    }

    /**
     * Processes the action to display the "help" page
     *
     * @return void
     */
    public function helpAction()
    {
        $this->view->information = 'information/help.phtml';
    }

    /**
     * Processes the action to display the "home" page
     *
     * @return void
     */
    public function homeAction()
    {
        $this->view->information = 'information/home.phtml';
    }

    /**
     * Processes the action to display a dictionary "introduction" page
     *
     * @return void
     */
    public function introductionAction()
    {
        if ($this->isInternalDictionary()) {
            $this->view->introduction = 'introduction/' . $this->dictionary['introduction'];
        } else {
            $this->view->externalDict = $this->dictionary['introduction'];
        }
    }

    /**
     * Sets data before processing an action
     *
     * @return void
     */
    public function init()
    {
        $this->flipActions();
        $this->setDictionary();
        $this->setWord();
        $this->setPage();
        $this->setVolume();
        $this->setNoDictChange();
    }

    public function isIE()
    {
	    return stripos($_SERVER['HTTP_USER_AGENT'], 'msie');
    }

    /**
     * Checks if the dictionary is internal or external
     *
     * @return boolean true if internal, false if external
     */
    public function isInternalDictionary()
    {
        return !empty($this->dictionary['internal']);
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
     * Processes the action to display the "options" page
     *
     * @return void
     */
    public function optionsAction()
    {
        $this->view->information = 'information/options.phtml';
    }

    /**
     * Processes the "go to a page" action
     *
     * @return void
     */
    public function pageAction($action = 'goToPage')
    {
        if ($this->isInternalDictionary()) {
            $search = $this->createSearchObject();
            $result = $search->$action($this->view->volume, $this->view->page);
            $this->setViewElements($result);
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
        if ($this->isInternalDictionary()) {
            $this->searchInternalDict();
        } else {
            $this->searchExternalDict();
        }
    }

    /**
     * Searches an external dictionary
     *
     * @return void
     * @TODO use dedicated search classes for each dictionary instead of switch()
     */
    public function searchExternalDict()
    {
        $word = $this->view->word;

        switch($this->dictionary['id']) {
            case 'jeanneau':
                $search = $this->createSearchObject();
                $this->view->externalDict = $search->searchWord($word);
                break;

            case 'leconjugueur':
            case 'littre':
            case 'whitaker':
                $string = new Base_String;
                $word = $string->utf8toASCII($word);
                $this->view->externalDict = $this->dictionary['search'] . $word;
                break;

            case 'cotgrave':
                $string = new Base_String;
                $word = $string->utf8toASCII($word);

                if ($word <= 'ABBAISSEUR') {
                    $word = 'ABB';
                }

                $this->view->externalDict = $this->dictionary['search'] . $word;
                break;

            default:
                $this->view->externalDict = $this->dictionary['search'] . $word;
        }
    }

    /**
     * Searches an internal dictionary
     *
     * @return void
     */
    public function searchInternalDict()
    {
        $search = $this->createSearchObject();
        $result = $search->searchWord($this->view->word);

        if ($this->dictionary['id'] == 'tcaf') {
            list($this->view->tcaf, $this->view->composedVerbs) = $result;
        } else {
            $this->setViewElements($result);
        }
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

        $arguments[0] = $this->translateActions($arguments[0]);

        $link = implode('/', $arguments);

        if ($this->view->noDictChange) {
            $link .= '?no-dict-change=1';
        }

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

        if (isset($this->actionsFlipped[$action])) {
            $action = $this->actionsFlipped[$action];
        } else {
            $action = 'home';
        }

        return $this->front->action = $this->completeActions($action);
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
     * Validates and sets the dictionary
     *
     * @return void
     */
    public function setDictionary()
    {
        $url = array_shift($this->front->actionParams);
        $this->dictionary = $this->findDictionary($url);

        if ($this->isInternalDictionary() and empty($this->dictionary['search']['properties']['dictionary'])) {
            // defaults search properties
            $this->dictionary['search']['properties']['dictionary'] = $this->dictionary['id'];
        }

        if (empty($this->dictionary['introduction'])) {
            // defaults introduction
            $this->dictionary['introduction'] = "{$this->dictionary['id']}.phtml";
        }

        if (empty($this->dictionary['url'])) {
            // defaults url
            $this->dictionary['url'] = $this->dictionary['id'];
        }
    }

    /**
     * Freezes the dictionary if requested
     *
     * For example, when DicFro is called from MultiDic, the select box to change
     * dictionaries is disabled
     *
     * @return void
     */
    public function setNoDictChange()
    {
        $this->view->noDictChange = !empty($this->front->params['no-dict-change']);
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
     * Sets view elements
     *
     * @param  array $elements the elements coming from a model
     * @return void
     */
    public function setViewElements($elements)
    {
        list($content,
            $this->view->errataImages,
            $this->view->errataText,
            $this->view->ghostwords,
            $this->view->identifiedWords,
            $this->view->identifiedVerbs,
            $this->view->identifiedLatinWords,
            $this->view->volume,
            $this->view->page,
            $this->view->firstWord) = $elements;

        if ($this->dictionary['id'] == 'lexromv') {
            $this->view->vocabulary = $content;
        } else {
            $this->view->definition = $content;
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

    /**
     * Translates an action into a method name
     *
     * Note: the name of this method must not finish with "Action"
     * so it is not mistaken with a proper action
     *
     * @param  string $action the action name to translate
     * @return string the translated action name
     */
    public function translateActions($action)
    {
        if (isset($this->actions[$action])) {
            $action = $this->actions[$action];
        }

        return $action;
    }
}