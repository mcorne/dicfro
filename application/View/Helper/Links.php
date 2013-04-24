<?php

/**
 * Dicfro
 *
 * PHP 5
 *
 * @category   DicFro
 * @package    View
 * @subpackage Helper
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2013 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

require_once 'View/Helper/Base.php';

/**
 * Links View Helper
 *
 * @category   DicFro
 * @package    View
 * @subpackage Helper
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2013 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class View_Helper_Link extends View_Helper_Base
{
    /**
     * Links parameters
     *
     * @var array
     */
    public $arguments;

    /**
     * Create a simple link
     *
     * @param  string $name
     * @param  array  $arguments
     * @return string
     */
    public function __call($name, $arguments)
    {
        static $link = null;

        if (! isset($link[$name])) {
            $link[$name] = $this->setLink($this->arguments[$name]);
        }

        return $link[$name];
    }

    /*
     * Sets the links parameters
     *
     * @see View_Helper_Base::init()
     */
    public function init()
    {
        $this->arguments = array(
            'setAboutLink'        => 'about',
            'setArchivesLink'     => 'archives',
            'setDictionaryLink'   => array($this->view->action, '%s', $this->view->word),
            'setDictionariesLink' => 'dictionaries',
            'setDictlistLink'     => 'dictlist',
            'setHomeLink'         => 'home',
            'setIntroductionLink' => array('introduction', $this->view->dictionary['url']),
            'setNextPageLink'     => array('next', $this->view->dictionary['url'], $this->view->page, $this->view->volume, $this->view->word),
            'setOptionsLink'      => 'options',
            'setPreviousPageLink' => array('previous', $this->view->dictionary['url'], $this->view->page, $this->view->volume, $this->view->word),
            'setWordLink'         => array('search', $this->view->dictionary['url'], '%s'),
        );
    }

    /**
     * Sets the go to a page link
     *
     * @return string
     */
    public function setGoPageLink()
    {
        static $link = null;

        if (! isset($link)) {
            if (empty($this->view->dictionary['volume'])) {
                $link = $this->setLink(array('page', $this->view->dictionary['url'], '%s', $this->view->word));
            } else {
                $link = $this->setLink(array('page', $this->view->dictionary['url'], '%s', '%s', $this->view->word));
            }
        }

        return $link;
    }

    /**
     * Sets the home link
     *
     * @return string
     */
    public function setHomeLink()
    {
        static $link = null;

        if (! isset($link)) {
            if (empty($this->view->params['open-dict-in-new-tab'])) {
                $link = $this->setLink('home');
            } else {
                $link = $this->setLink(array('home', $this->view->dictionary['url']));
            }
        }

        return $link;
    }

    /**
     * Sets a link
     *
     * @param $arguments
     * @return string
     */
    public function setLink($arguments = array())
    {
        $arguments = array_filter((array) $arguments);
        array_unshift($arguments, $this->view->baseUrl);

        return implode('/', $arguments);
    }
}