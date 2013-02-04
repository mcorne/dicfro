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

/**
 * Test run
 *
 * @category   DicFro
 * @package    Tests
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2010-2013 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class Test {

    /**
     * The name of the sub directory of the code coverage report
     */
    const COVERAGE_DIR = 'coverage';

    /**
     * The help text
     */
    const HELP_TEXT =
'
Usage: runtest [option] [value]

a         -- runs all the tests
c         -- runs the test code coverage
r         -- reruns the last command
s <test>  -- runs a specific test
u         -- runs the recently updated tests
v         -- runs all the tests in verbose mode

h         -- list options (help)
';

    /**
     * The PHP extension directory full path to override ini_get('extension_dir') if necessary
     *
     * Example: "C:/Program Files (x86)/Zend/ZendServer/lib/phpext"
     *
     * @var string
     */
    const PHP_EXTENSION_DIR = null;

    /**
     * The PHP ini file full path to override php_ini_loaded_file() if necessary
     *
     * Example: "D:/Data/dicfro/tests/php.ini"
     *
     * @var string
     */
    const PHP_INI_FILE = null;

    /**
     * The Pear binary directory full path to override getenv('PHP_PEAR_BIN_DIR') if necessary
     *
     * Example: "C:/pear"
     *
     * @var string
     */
    const PHP_PEAR_BIN_DIR = null;

    /**
     * The relative base directory where the test results are stored
     */
    const PHPUNIT_TEST_RESULTS_DIR = 'results';

    /**
     * The name of the file containing the test results
     */
    const TESTDOX_FILE = 'testdox.html';

    /**
     * The relative name of the temporary working directory
     */
    const TMP_DIR = 'tmp';

    /**
     * XDebug extension base name to override the automatic detection of the Xdebug extension if necessary
     *
     * Example: "D:/Data/dicfro/tests/php_xdebug-2.2.1-5.3-vc9-nts.dll"
     *
     * @var string
     */
    const XDEBUG_EXTENSION = null;

    /**
     * Creates the temporary directory
     */
    public static function createTempDir()
    {
        self::removeTempDir();
        $tmpDir = self::getTempDir();
        @mkdir($tmpDir) or die('cannot create temp directory');
    }

    /**
     * Returns the name of the temporary directory
     *
     * @return string the name of the temporary directory
     */
    public static function getTempDir()
    {
        self::TMP_DIR or die('missing temp directory!!!');

        return dirname(__FILE__) . '/' . self::TMP_DIR;
    }

    /**
     * Creates the temporary PHP ini file to load the xdebug extension
     *
     * @param string $xdebugExtension
     * @return string
     */
    public function makeTestCoveragePhpIniFile($xdebugExtension)
    {
        $phpIniFile = php_ini_loaded_file();
        $content = file_get_contents($phpIniFile) or die("Error: cannot read PHP ini file: $phpIniFile");
        $content = preg_replace('~^zend_extension=.*?$~m', '', $content);
        $content .= "\nzend_extension=\"$xdebugExtension\"";

        self::createTempDir();
        $phpIniFile = self::getTempDir() .  '/php.ini';
        file_put_contents($phpIniFile, $content) or die("Error: cannot write PHP ini file: $phpIniFile");

        return $phpIniFile;
    }

    /**
     * Prepares the test coverage command
     *
     * @return array
     */
    public function prepareTestCoverageCommand()
    {
        $pearDir = self::PHP_PEAR_BIN_DIR ? self::PHP_PEAR_BIN_DIR : getenv('PHP_PEAR_BIN_DIR');
        is_dir($pearDir) or die("Error: cannot access pear directory: $pearDir");

        $extensionDir = self::PHP_EXTENSION_DIR ? self::PHP_EXTENSION_DIR : ini_get('extension_dir');
        is_dir($extensionDir) or die("Error: cannot access extension directory: $extensionDir");

        if (self::XDEBUG_EXTENSION) {
            $xdebugExtension = $extensionDir . '/' . self::XDEBUG_EXTENSION;
        } else {
            $xdebugExtension = glob("$extensionDir/php_xdebug*") or die("Error: xdebug not found: $xdebugExtension");
            list($xdebugExtension) = $xdebugExtension;
        }
        is_readable($xdebugExtension) or die("Error: cannot access Xdebug extension: $xdebugExtension");

        if (self::PHP_INI_FILE) {
            $phpIniFile = self::PHP_INI_FILE;
        } else {
            $phpIniFile = $this->makeTestCoveragePhpIniFile($xdebugExtension);
        }
        is_readable($phpIniFile) or die("Error: cannot access PHP ini file: $phpIniFile");

        return array($phpIniFile, $pearDir);
    }

    /**
     * Processes the command option
     *
     * @return string the result of the command
     */
    public function processOption()
    {
        global $argv;
        $option = isset($argv[1])? $argv[1] : null;
        $option = str_replace('-', '', $option);
        $param = isset($argv[2])? $argv[2] : null;

        switch($option) {
         case 'a':
             // runs all the tests
             putenv('RUN_ALL_TESTS=1');
             $command = 'phpunit --stop-on-failure AllTests';
             break;

         case 'c':
             // runs the test code coverage
             $phpunitDir = dirname(__FILE__) . '/' . self::PHPUNIT_TEST_RESULTS_DIR;
             $coverageDir = $phpunitDir . '/' . self::COVERAGE_DIR;
             $testdoxFile = $phpunitDir . '/' . self::TESTDOX_FILE;

             is_dir($phpunitDir) or die('Error: invalid phpunit directory');
             is_dir($coverageDir) or die('Error: invalid test code coverage directory');

             putenv('RUN_ALL_TESTS=1');

             if (extension_loaded('Xdebug')) {
                 $command = "phpunit --testdox-html $testdoxFile --coverage-html $coverageDir AllTests";
             } else {
                 list($phpIniFile, $pearDir) = $this->prepareTestCoverageCommand();
                 $command = "php -c \"$phpIniFile\" \"$pearDir/phpunit\" --testdox-html $testdoxFile --coverage-html $coverageDir AllTests";
             }
             break;

         case 'r':
             // reruns the last command
             $command = "phpunit --stop-on-failure AllTests";
             break;

         case 's':
             // runs a specific test
             $param or die('Error: enter the test to run !');

             putenv('RUN_ALL_TESTS=1');
             $command = "phpunit --stop-on-failure --filter $param .";
             break;

         case 'u':
             // runs the updated tests
             putenv('RUN_ALL_TESTS=0');
             $command = 'phpunit --stop-on-failure AllTests';
             break;

         case 'v':
             // runs all tests in verbose mode
             putenv('RUN_ALL_TESTS=1');
             $command = 'phpunit --verbose AllTests';
             break;

         case 'h':
         default:
             $command = null;
        }

        return $command;
    }

    /**
     * Removes the temporary directory
     */
    public static function removeTempDir()
    {
        $tmpDir = self::getTempDir();

        array_map('unlink', glob("$tmpDir/*.*"));
        array_map('unlink', glob("$tmpDir/*/*.*"));
        array_map('unlink', glob("$tmpDir/*/*/*.*"));
        array_map('unlink', glob("$tmpDir/*/*/*/*.*"));
        array_map('unlink', glob("$tmpDir/*/*/*/*/*.*"));
        array_map('rmdir', glob("$tmpDir/*/*/*/*", GLOB_ONLYDIR));
        array_map('rmdir', glob("$tmpDir/*/*/*", GLOB_ONLYDIR));
        array_map('rmdir', glob("$tmpDir/*/*", GLOB_ONLYDIR));
        array_map('rmdir', glob("$tmpDir/*", GLOB_ONLYDIR));
        is_dir($tmpDir) and rmdir($tmpDir);
    }

    /**
     * Runs the command
     *
     * @return void
     */
    public function run()
    {
        $command = $this->processOption();
        if ($command) {
            echo "$command\n";
            passthru($command);

        } else {
            echo self::HELP_TEXT;
        }

        self::removeTempDir();
    }
}