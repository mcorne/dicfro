<?php
/**
 * Dicfro
 *
 * PHP 5
 *
 * @category   DicFro
 * @package    Tests
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2010-2013 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

define('APPLICATION_DIR', dirname(__FILE__) . '/../application/');
is_dir(APPLICATION_DIR) or die('Error: invalid application directory!');

define('APPLICATION_TEST_DIR', dirname(__FILE__) . '/application/');
is_dir(APPLICATION_TEST_DIR) or die('Error: invalid application test directory!');

defined('PHPUnit_MAIN_METHOD') or define('PHPUnit_MAIN_METHOD', 'AllTests::main');

// require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'Filter.php';
require_once 'Test.php';

set_include_path(APPLICATION_DIR . PATH_SEPARATOR . get_include_path());

// removes test directory for test code coverage report
// PHPUnit_Util_Filter::addDirectoryToFilter(dirname(__FILE__), array('.php')); TODO: fix

date_default_timezone_set('UTC');

/**
 * Test suite
 *
 * @category   DicFro
 * @package    Tests
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2010 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class AllTests
{
    /**
     * Runs the tests
     *
     * @param  array $files the files to test
     * @return void
     */
    public static function main($files)
    {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    /**
     * Runs the tests
     *
     * @param  array $files the files to test
     * @return object  the test suite object
     */
    public static function suite($files)
    {
        $suite = new PHPUnit_Framework_TestSuite('DicFro Tests');

        if ($files = Filter::listTestFiles(APPLICATION_DIR, APPLICATION_TEST_DIR)) {
            $message = "Files to test ...\n" . implode("\n", $files);
        } else {
            $message = "No files to test!";
        }
        echo "$message\n\n";

        $suite->addTestFiles($files);

        return $suite;
    }
}

PHPUnit_MAIN_METHOD == 'AllTests::main' and AllTests::main($files);
