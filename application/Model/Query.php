<?php
/**
 * Dicfro
 *
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2015 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

require_once 'Base/String.php';

/**
 * Queries a dictionary database
 */
abstract class Model_Query
{
    static public $debug = false;
    public $dictionaryId;
    public $string; // Base_String
    static public $trace = [];

    /**
     * @param array $properties
     * @param string $dictionaryId
     */
    public function __construct($properties = null, $dictionaryId = null)
    {
        if (! $dictionaryId) {
            $dictionaryId = $this->dictionaryId;
        }

        $this->dsn = sprintf('sqlite:%s/../data/%s/dictionary.sqlite', __DIR__, $dictionaryId);

        foreach((array) $properties as $property => $value) {
            $this->$property = $value;
        }

        $this->string = new Base_String;
    }

    /**
     * @param array $result
     */
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

    /**
     * @param mixed $arg
     * @return string
     */
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

    /**
     * @param aray $trace
     * @return string
     *
     */
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

    /**
     * @param mixed $arg
     * @return array|string
     */
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
