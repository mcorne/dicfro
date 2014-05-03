<?php

/**
 * Dicfro
 *
 * PHP 5
 *
 * @category   DicFro
 * @package    Model
 * @subpackage Query
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2014 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

require_once 'Base/String.php';

/**
 * Queries a dictionary database
 *
 * @category   DicFro
 * @package    Model
 * @subpackage Query
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2010 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

abstract class Model_Query
{
    /**
     * Template of the dictionary database name
     */
    const DSN_TPL = 'sqlite:%s/dictionary.sqlite';

    /**
     * Name of the dictionary database
     * @var string
     */
    public $dsn;

    /**
     * String object
     * @var object
     */
    public $string;

    /**
     * Constructor
     *
     * @param  string $directory the dictionaries directory
     * @return void
     */
    public function __construct($directory, $properties = array())
    {
        foreach($properties as $property => $value) {
            $this->$property = $value;
        }

        // sets the dictionary database name
        $this->dsn = $this->createDsn($directory);

        $this->string = new Base_String;
    }

    /**
     * Creates the name of the dictionary database
     *
     * @param  string $directory the dictionaries directory
     * @return string the name of the dictionary database
     */
    public function createDsn($directory)
    {
        return sprintf(self::DSN_TPL, $directory);
    }

    /**
     * Prepares and executes a query and fetches the first row
     *
     * @param  string    $query      the SQL query
     * @param  array     $parameters the list of parameters, format: array(<key> => <value>,...)
     * @param  string    $fetchStyle the fetch style
     * @return mixed     the result of the query
     * @throws Exception if no result is returned
     */
    public function fetch($query, $parameters = array(), $fetchStyle = PDO::FETCH_ASSOC)
    {
        $pdo = new PDO($this->dsn) and
        $statement = $pdo->prepare($query) and
        $statement->execute($parameters) and
        $result = $statement->fetch($fetchStyle);

        if (!isset($result)) {
            throw new Exception('unexpected query error');
        }

        return $result;
    }

    /**
     * Prepares and executes a query and fetches all rows
     *
     * @param  string    $query      the SQL query
     * @param  array     $parameters the list of parameters, format: array(<key> => <value>,...)
     * @param  string    $fetchStyle the fetch style
     * @return mixed     the result of the query
     * @throws Exception if no result is returned
     */
    public function fetchAll($query, $parameters = array(), $fetchStyle = PDO::FETCH_ASSOC)
    {
        $pdo = new PDO($this->dsn) and
        $statement = $pdo->prepare($query) and
        $statement->execute($parameters) and
        $result = $statement->fetchAll($fetchStyle);

        if (!isset($result)) {
            throw new Exception('unexpected query error');
        }

        return $result;
    }
}