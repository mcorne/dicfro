<?php
/**
 * Dicfro
 *
 * PHP 5
 *
 * @category   DicFro
 * @package    Tests
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2010 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

/**
 * Tests filter
 *
 * @category   DicFro
 * @package    Tests
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2010 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class Filter
{
    /**
     * The name of the file updated each time the test is run
     */
    const LAST_TEST_RUN = 'last-test-run.php';

    /**
     * The name of the file containing the class list and their dependencies
     */
    const DEPENDENCIES = 'Dependencies.php';

    /**
     * The class list and their dependencies
     * @var array
     */
    protected $dependencies = array();

    /**
     * The name of the application directory
     * @var string
     */
    protected $applicationDir;

    /**
     * The name of the application test directory
     * @var string
     */
    protected $applicationTestDir;

    /**
     * The full name of the file updated each time the test is run
     * @var string
     */
    protected $lastTestRunFile;

    /**
     * Constructor
     *
     * @param string $applicationDir     the name of the application directory
     * @param string $applicationTestDir the name of the application test directory
     * @return void
     */
    public function __construct($applicationDir, $applicationTestDir)
    {
        $this->applicationDir = $applicationDir;
        $this->applicationTestDir = $applicationTestDir;

        $this->dependencies = include dirname(__FILE__) . '/' . self::DEPENDENCIES;
        $this->lastTestRunFile = dirname(__FILE__) . '/' . self::LAST_TEST_RUN;
    }

    /**
     * Determines the last update time of a file or its dependencies
     *
     * @param  string $file the file name
     * @return array  the last update time
     */
    public function getFileTime($file)
    {
        static $times = array();

        if (!isset($times[$file])) {
            $times[$file] = filemtime($this->applicationDir . $file);
            $dependencyTime = 0;

            foreach($this->dependencies[$file] as $dependency) {
                $dependencyTime = $this->getFileTime($dependency);
            }

            $times[$file] = max($dependencyTime, $times[$file]);
        }

        return $times[$file];
    }

    /**
     * Completes the list of dependencies
     *
     * @return void
     */
    public function completeDependencies()
    {
        foreach($this->dependencies as $details) {
            foreach($details as $dependency) {
                isset($this->dependencies[$dependency]) or $this->dependencies[$dependency] = array();
            }
        }
    }

    /**
     * Removes a file from the list of dependencies
     *
     * @param  string $file         the file name
     * @param  array  $dependencies the list of dependencies
     * @return array  the updated list of dependencies
     */
    public function removeDependencies($file, $dependencies)
    {
        // removes the file from all dependent files
        foreach($dependencies as &$dependency) {
            unset($dependency[$file]);
        }

        // removes the file from the dependency list
        unset($dependencies[$file]);

        return $dependencies;
    }

    /**
     * Finds the tests to run
     *
     * A test is run if the test file, or the class file, or one of its dependencies
     * was updated since the last run.
     *
     * @param  bool  $runAllTests all tests are run if true, or run as needed if false
     * @return array the list of tests to run
     */
    public function findTests($runAllTests)
    {
        $this->completeDependencies();

        $dependencies = array_map('array_flip', $this->dependencies);

        $lastRunTime = file_exists($this->lastTestRunFile)? filemtime($this->lastTestRunFile) : time();

        $tests = array();
        $recentFiles = array();

        while(count($dependencies)) {
            $count = array_map('count', $dependencies);
            asort($count);

            $file = key($count);
            $dependencies = $this->removeDependencies($file, $dependencies);

            ($runAllTests or $this->getFileTime($file) > $lastRunTime or
            array_intersect($this->dependencies[$file], $recentFiles)) and
            $recentFiles[] = $file;

            if (pathinfo($file, PATHINFO_EXTENSION) == 'php') {
                $testFile = $this->applicationTestDir . str_replace('.php', 'Test.php', $file);
                file_exists($testFile) and
                (filemtime($testFile) > $lastRunTime or in_array($file, $recentFiles)) and
                $tests[] = $testFile;
            }
        }

        // stores the list of tests to run
        $content = var_export($tests, true);
        file_put_contents($this->lastTestRunFile, "<?php return $content;");

        return $tests;
    }

    /**
     * Finds the tests to run
     *
     * @param string $applicationDir     the name of the application directory
     * @param string $applicationTestDir the name of the application test directory
     */
    public static function listTestFiles($applicationDir, $applicationTestDir)
    {
        $filter = new Filter($applicationDir, $applicationTestDir);

        $runAllTests = getenv('RUN_ALL_TESTS');
        if ($runAllTests === false) {
            // the directive is not set, returns the last test set
            $tests = file_exists($filter->lastTestRunFile)? include $filter->lastTestRunFile : array();
        } else {
            // the directive is set, finds the test to run
            $tests = $filter->findTests($runAllTests);
        }

        return $tests;
    }
}
