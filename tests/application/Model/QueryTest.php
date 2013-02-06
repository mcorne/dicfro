<?php
/**
 * Dicfro
 *
 * PHP 5
 *
 * @category   Application
 * @package    DicFro
 * @subpackage Tests
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2013 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

require_once "PHPUnit/Framework/TestCase.php";
require_once "PHPUnit/Framework/TestSuite.php";

require_once 'Test.php';

require_once 'Model/Query.php';

/**
 * Query class tests
 *
 * @category   Application
 * @package    DicFro
 * @subpackage Tests
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2013 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class QueryTest extends PHPUnit_Framework_TestCase
{
    /**
     * The Query class instance
     * @var Model_QueryExtended
     */
    public $query;

    /**
     * Prepares a test
     */
    protected function setUp()
    {
        Test::createTempDir();
        $this->query = new Model_QueryExtended(Test::getTempDir());
    }

    /**
     * Tests createDsn
     */
    public function testCreateDsn()
    {
        $this->assertSame(
            sprintf('sqlite:%s/dictionary.sqlite', Test::getTempDir()),
            $this->query->createDsn(Test::getTempDir()),
             'creating dns');
    }

    /**
     * Tests execute
     */
    public function testExecute()
    {
        $pdo = new PDO($this->query->dsn);
        $pdo->query('CREATE TABLE test (name)');
        $pdo->query('INSERT INTO test (name) VALUES ("abc")');
        $pdo->query('INSERT INTO test (name) VALUES ("def")');
        $pdo->query('INSERT INTO test (name) VALUES ("ghi")');
        $result = $pdo->query('SELECT * FROM test');

        $sql = 'SELECT * FROM test';

        $this->assertSame(
            array(
                array('name' => 'abc'),
                array('name' => 'def'),
                array('name' => 'ghi'),
            ),
            $this->query->execute($sql),
             'selecting all rows');

        /**********/

        $this->assertSame(
            array(
                'abc',
                'def',
                'ghi',
            ),
            $this->query->execute($sql, null, PDO::FETCH_COLUMN),
             'selecting all rows one column');

        /**********/

        $sql = 'SELECT * FROM test WHERE name=:name';

        $this->assertSame(
            array(
                array('name' => 'abc'),
            ),
            $this->query->execute($sql, array(':name' => 'abc')),
             'selecting one row');

        /**********/

        try {
            $this->query->execute('');
            $result = 'should not get here - should have caught exception';
        } catch (Exception $e) {
            $result = $e->getMessage();
        }

        $this->assertSame(
            'query-error',
            $result,
             'catching exception');
    }
}

class Model_QueryExtended extends Model_Query
{
}
