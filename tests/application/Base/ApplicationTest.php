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

require_once 'Base/Application.php';

/**
 * Application class tests
 *
 * @category   Application
 * @package    DicFro
 * @subpackage Tests
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2013 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class ApplicationTest extends PHPUnit_Framework_TestCase
{
    /**
     * The Application class instance
     * @var Base_Application
     */
    public $application;

    /**
     * Prepares a test
     */
    protected function setUp()
    {
        Test::createTempDir();

        $config = array(
            'data-dir'  => Test::getTempDir(),
            'dictionaries' => array(
                'uvw' => array(
                    'language' => 'fr',
                ),
            ),
            'domain-subpath' => null,
            'views-dir' => Test::getTempDir(),
        );

        $content = '<?php return ' . var_export($config, true) . ';';
        $configFile = Test::getTempDir() . '/config.php';
        file_put_contents($configFile, $content);

        $this->application = new Base_Application($configFile);
    }

    /**
     * Tests run
     */
    public function testRun()
    {
        $_SERVER['HTTP_HOST'] = 'www.abc.com';
        $_SERVER['REQUEST_URI'] = '/action/uvw/123/456/abc';
        $_SERVER['HTTP_USER_AGENT'] = '';
        $_GET = array(); // must empty because it is set if run within ZS in CGI mode

        mkdir(Test::getTempDir() . '/interface');
        file_put_contents(Test::getTempDir() . '/interface/layout.phtml', 'abc');

        ob_start();
        $this->application->run();
        $content = ob_get_clean();

        $this->assertSame(
            'abc',
            $content,
            'running application');
    }
}