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
 * @copyright  2008-2010 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

abstract class Model_Search
{
    public function __construct($directory, $properties = array(), $query = array())
    {
        foreach($properties as $property => $value) {
            $this->$property = $value;
        }

        if (isset($query['class'])) {
            $class = $query['class'];
            $file = str_replace('_', '/', $class) . '.php';
            require_once $file;

            $directory .= '/' . $this->dictionary;
            $properties = isset($query['properties'])? $query['properties'] : array();

            $this->query = new $class($directory, $properties);
        }
    }
}