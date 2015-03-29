<?php
/**
 * Dicfro
 *
 * @author    Michel Corne <mcorne@yahoo.com>
 * @copyright 2008-2015 Michel Corne
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

/**
 * View engine
 */
class Base_View
{
    public $baseUrl;
    public $config;
    public $viewsDir;

    /**
     * @param array $config
     */
    public function __construct($config)
    {
        $this->config = $config;
        $this->viewsDir = __DIR__ . '/../View/scripts';
        $this->setBaseUrl();
    }

    /**
     * @param string $name
     * @return View_Helper_Base|null
     */
    public function __get($name)
    {
        if (! preg_match('~^(\w+)Helper$~', $name, $match)) {
            return null;
        }

        // eg "dictionariesHelper"

        $basename = ucfirst($match[1]); // eg "Dictionaries"
        require_once "View/Helper/$basename.php";

        $className  = "View_Helper_$basename"; // eg "View_Helper_Dictionaries"
        $helper = new $className($this);

        return $helper;
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
     * Displays the time rounded to the most significant digit
     *
     * @param number $time
     * @return float
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
     * @param string $string
     * @return string
     */
    public function escape($string)
    {
        return htmlspecialchars($string, ENT_COMPAT, 'UTF-8');
    }

    public function render()
    {
        include "{$this->viewsDir}/interface/layout.phtml";
    }

    public function setBaseUrl()
    {
        $this->baseUrl = 'http://' . $_SERVER['HTTP_HOST'];

        if (! empty($this->config['domain-subpath'])) {
            $this->baseUrl .= '/' . $this->config['domain-subpath'];
        }
    }

    /**
     * @param string $link
     * @return string
     */
    public function setLinkUrl($link)
    {
        $url = $this->baseUrl . '/' . $link;

        return $url;
    }

    /**
     * Shortcut to the translator
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

