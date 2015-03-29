<?php
/**
 * Dicfro
 *
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2015 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

/**
 * Searches a dictionary
 */
abstract class Model_Search
{
    public $dictionary;
    public $dictionaryDir;
    public $query; // Model_Query
    public $queryClass;

    /**
     * @param array $properties
     * @param array $query
     * @param string $dictionaryDir
     */
    public function __construct($properties = null, $query = null, $dictionaryDir = null)
    {
        foreach((array) $properties as $property => $value) {
            $this->$property = $value;
        }

        if (is_array($query) or $this->queryClass) {
            $this->createQuery($query);
        }

        $this->dictionaryDir = $dictionaryDir;
    }

    /**
     * @param mixed $query
     */
    public function createQuery($query)
    {
        $queryClass = isset($query['class']) ? $query['class'] : $this->queryClass;

        $file = str_replace('_', '/', $queryClass) . '.php';
        require_once $file;

        $properties = isset($query['properties']) ? $query['properties'] : null;
        $this->query = new $queryClass($properties, $this->dictionary);
    }
}
