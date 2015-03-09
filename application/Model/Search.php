<?php

/**
 * Dicfro
 *
 * PHP 5
 *
 * @category   DicFro
 * @package    Model
 * @subpackage Search
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2010 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

// the query class is included as needed

/**
 * Search a dictionary
 *
 * @category   DicFro
 * @package    Model
 * @subpackage Search
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2015 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

abstract class Model_Search
{
    /**
     * Name of the dictionary
     * @var string
     */
    public $dictionary;

    /**
     * Dictionary directory containting images (dictionary pages)
     * @var string
     */
    public $dictionaryDir;

    /**
     * Query object
     * @var object
     */
    public $query;

    public function __construct($directory, $properties = [], $query = [], $dictionaryDir = null)
    {
        foreach($properties as $property => $value) {
            $this->$property = $value;
        }

        $this->dictionaryDir = $dictionaryDir;

        if (isset($query['class'])) {
            $class = $query['class'];
            $file = str_replace('_', '/', $class) . '.php';
            require_once $file;

            $directory .= '/' . $this->dictionary;

            if (isset($query['properties'])) {
                $properties = $query['properties'];
            } else {
                $properties = [];
            }

            $this->query = new $class($directory, $properties);
        }
    }
}