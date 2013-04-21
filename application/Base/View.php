<?php

/**
 * Dicfro
 *
 * PHP 5
 *
 * @category  DicFro
 * @package   Base
 * @author    Michel Corne <mcorne@yahoo.com>
 * @copyright 2008-2013 Michel Corne
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

require_once 'View/Helper/Dictionaries.php';
require_once 'View/Helper/Entries.php';
require_once 'View/Helper/Images.php';
require_once 'View/Helper/Translator.php';
require_once 'View/Helper/Verbs.php';
require_once 'View/Helper/Words.php';

/**
 * View engine
 *
 * @category  DicFro
 * @package   Base
 * @author    Michel Corne <mcorne@yahoo.com>
 * @copyright 2008-2013 Michel Corne
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
     * @var View_Helper_Dictionaries
     */
    public $dictionariesHelper;

    /**
     * Images view helper
     * @var View_Helper_Images
     */
    public $imagesHelper;

    /**
     * Translator view helper
     * @var View_Helper_Translator
     */
    public $translatorHelper;

    /**
     * Verbs view helper
     * @var View_Helper_Verbs
     */
    public $verbsHelper;

    /**
     * Directory containing the views
     * @var string
     */
    public $viewsDir;

    /**
     * View helper for identified words
     * @var View_Helper_Words
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

        if ($init) {
            $this->init();
        }
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
     * Assigns data to view properties
     *
     * @param array $data
     */
    public function assign($data)
    {
        foreach ($data as $key => $val) {
            $this->$key = $val;
        }
    }

    /**
     * Displays time rounded to the most significant digit
     *
     * @param number $time
     * @return number
     */
    public function displayTime($time)
    {
        $rounded = (float)round($time, 1) or
        $rounded = (float)round($time, 2) or
        $rounded = (float)round($time, 3) or
        $rounded = (float)round($time, 4) or
        $rounded = (float)round($time, 5) or
        $rounded = (float)round($time, 6);

       return $rounded;
    }

    /**
     * Sets resources used by the view
     *
     * @return void
     */
    public function init()
    {
        $this->viewsDir = $this->config['views-dir'];
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

        if ($action) {
            $actionUrl .= $action;
        }

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

        if (! empty($this->config['domain-subpath'])) {
            $this->baseUrl .= '/' . $this->config['domain-subpath'];
        }
    }

    /**
     * Sets Helpers
     *
     * @return void
     */
    public function setHelpers()
    {
        $this->dictionariesHelper = new View_Helper_Dictionaries($this);
        $this->entriesHelper      = new View_Helper_Entries($this);
        $this->imagesHelper       = new View_Helper_Images($this);
        $this->translatorHelper   = new View_Helper_Translator($this);
        $this->verbsHelper        = new View_Helper_Verbs($this);
        $this->wordsHelper        = new View_Helper_Words($this);
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

        if ($filter) {
            // filters out empty properties
            $dynamicProperties = array_filter($dynamicProperties);
        }

        ksort($dynamicProperties);

        return $dynamicProperties;
    }

    /**
     * shortcut to the translator
     *
     * @param string $string
     * @param string $english
     * @param bool $translate
     * @return string
     */
    public function translate($string, $english = null, $translate = true)
    {
        return $this->translatorHelper->translate($string, $english, $translate);
    }
}