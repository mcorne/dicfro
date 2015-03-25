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

    static public $debug = false;

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

    static public $trace = [];

    /**
     * Constructor
     *
     * @param  string $directory the dictionaries directory
     * @return void
     */
    public function __construct($directory, $properties = [])
    {
        foreach((array) $properties as $property => $value) {
            $this->$property = $value;
        }

        // sets the dictionary database name
        $this->dsn = $this->createDsn($directory);

        $this->string = new Base_String;
    }

    public function addDebugTrace($result)
    {
        $trace = debug_backtrace(0);
        $trace = array_slice($trace, 1, -4);
        $trace = array_reverse($trace);
        $calls = array_map([$this, 'formatCall'], $trace);
        $query = array_pop($calls);

        self::$trace[] = [
            'call-stack' => $calls,
            'query'      => $query,
            'result'     => $result,
        ];
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
     * @param  array     $parameters the list of parameters, format: [<key> => <value>,...]
     * @param  string    $fetchStyle the fetch style
     * @return mixed     the result of the query
     * @throws Exception if no result is returned
     */
    public function fetch($query, $parameters = [], $fetchStyle = PDO::FETCH_ASSOC)
    {
        $pdo = new PDO($this->dsn) and
        $statement = $pdo->prepare($query) and
        $statement->execute($parameters) and
        $result = $statement->fetch($fetchStyle);

        if (self::$debug) {
            $this->addDebugTrace($result);
        }

        if (!isset($result)) {
            throw new Exception('unexpected query error');
        }

        return $result;
    }

    /**
     * Prepares and executes a query and fetches all rows
     *
     * @param  string    $query      the SQL query
     * @param  array     $parameters the list of parameters, format: [<key> => <value>,...]
     * @param  string    $fetchStyle the fetch style
     * @return mixed     the result of the query
     * @throws Exception if no result is returned
     */
    public function fetchAll($query, $parameters = [], $fetchStyle = PDO::FETCH_ASSOC)
    {
        $pdo = new PDO($this->dsn) and
        $statement = $pdo->prepare($query) and
        $statement->execute($parameters) and
        $result = $statement->fetchAll($fetchStyle);

        if (self::$debug) {
            $this->addDebugTrace($result);
        }

        if (!isset($result)) {
            throw new Exception('unexpected query error');
        }

        return $result;
    }

    public function formatArgument($arg)
    {
        $arg = $this->formatObject($arg);
        $arg = var_export($arg, true);
        $arg = preg_replace('~\s+~', ' ', $arg);
        $arg = str_replace('array ( ', '[', $arg);
        $arg = str_replace(' )', ']', $arg);
        $arg = str_replace(',]', ']', $arg);

        return $arg;
    }

    public function formatCall($trace)
{
        $args = array_map([$this, 'formatArgument'], $trace['args']);
        $args = implode(', ', $args);

        if (isset($trace['class'])) {
            $call = sprintf('%s::%s(%s)', $trace['class'], $trace['function'], $args);
        } else {
            $call = sprintf('%s(%s)', $trace['function'], $args);
        }

        return $call;
    }

    public function formatObject($arg)
    {
        if (is_object($arg)) {
            $arg = get_class($arg);
        } elseif (is_array($arg)) {
            $arg = array_map([$this, 'formatObject'], $arg);
        }

        return $arg;
    }
}
