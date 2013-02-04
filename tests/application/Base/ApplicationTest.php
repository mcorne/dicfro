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
 * @copyright  2008-2010 Michel Corne
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
 * @copyright  2008-2010 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class ApplicationTest extends PHPUnit_Framework_TestCase
{
    /**
     * The Application class instance
     * @var object
     */
    public $application;

    /**
     * Prepares a test
     */
    protected function setUp()
    {
        Test::createTempDir();

        $config = array(
            'data-dir' => Test::getTempDir(),
            'domain-subpath' => 'def',
            'views-dir' => 'ghi',
        );

        $this->application = new Base_Application($config);
    }

}