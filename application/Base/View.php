<?php

/**
 * Dicfro
 *
 * PHP 5
 *
 * @category  DicFro
 * @package   Base
 * @author    Michel Corne <mcorne@yahoo.com>
 * @copyright 2008-2012 Michel Corne
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

require_once 'View/Helper/Dictionaries.php';
require_once 'View/Helper/Images.php';
require_once 'View/Helper/Verbs.php';
require_once 'View/Helper/Words.php';

/**
 * View engine
 *
 * @category  DicFro
 * @package   Base
 * @author    Michel Corne <mcorne@yahoo.com>
 * @copyright 2008-2012 Michel Corne
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class Base_View
{

    /**
     * Base URL, ex. "http://micmap.org/dicfro"
     * @var string
     */
    public $baseUrl;

    /**
     * Configuration directives
     * @var array
     */
    public $config;

    /**
     * Dictionaries view helper
     * @var object
     */
    public $dictionariesHelper;

    /**
     * Images view helper
     * @var object
     */
    public $imagesHelper;

    /**
     * Verbs view helper
     * @var object
     */
    public $verbsHelper;

    /**
     * Directory containing the views
     * @var string
     */
    public $viewsDir;

    /**
     * View helper for identified words
     * @var object
     */
    public $wordsHelper;

    /**
     * Constructor
     *
     * @param  string $config the configuration directives
     * @return void
     */
    public function __construct($config, $init = true)
    {
        $this->config = $config;
        $init and $this->init();
    }

    /**
     * Property overloading getter
     *
     * @param  string $property the name of the property to get
     * @return null
     */
    public function __get($property)
    {
        return null;
    }

    /**
     * Sets resources used by the view
     *
     * @return void
     */
    public function init()
    {
        $this->viewsDir = $this->config['views-dir'];

        $this->dictionariesHelper = new View_Helper_Dictionaries($this);
        $this->imagesHelper = new View_Helper_Images($this);
        $this->verbsHelper = new View_Helper_Verbs($this);
        $this->wordsHelper = new View_Helper_Words($this);

        $this->setBaseUrl();
    }

    /**
     * Renders a view
     *
     * @param  string $viewName the name of the view
     * @return void
     */
    public function render($viewName = 'interface/layout.phtml')
    {
        include "{$this->viewsDir}/$viewName";
    }

    /**
     * Sets an action URL
     *
     * @param  string $action the name of the action
     * @return string the action URL
     */
    public function setActionUrl($action = null)
    {
        $actionUrl = $this->baseUrl . '/';
        $action and $actionUrl .= $action;

        return $actionUrl;
    }

    /**
     * Sets the base URL
     *
     * @return void
     */
    public function setBaseUrl()
    {
        $this->baseUrl = 'http://' . $_SERVER['HTTP_HOST'];
        empty($this->config['domain-subpath']) or $this->baseUrl .= '/' . $this->config['domain-subpath'];
    }

    /**
     * Sets a link URL
     *
     * @param  string  $link the name of the link
     * @return string  the link URL
     */
    public function setLinkUrl($link)
    {
        $url = $this->baseUrl . '/' . $link;

        return $url;
    }

    /**
     * Returns an array of properties set dynamically
     *
     * @param  boolean $filter flag to filter out empty properties or not
     * @param  array   $ignore list of properties to ignore
     * @return array   the list of properties and their values
     */
    public function toArray($filter = false, $ignore = array())
    {
        settype($ignore, 'array');

        // extracts the object properties excluding the class default properties
        $dynamicProperties = array_diff_key(get_object_vars($this), get_class_vars(__CLASS__));

        foreach($dynamicProperties as $key => $value) {
            if (in_array($key, $ignore)) {
                // ignores the property
                unset($dynamicProperties[$key]);
            }
        }

        // filters out empty properties
        $filter and $dynamicProperties = array_filter($dynamicProperties);

        ksort($dynamicProperties);

        return $dynamicProperties;
    }
}