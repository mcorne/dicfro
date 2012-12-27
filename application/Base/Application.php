<?php

/**
 * Dicfro
 *
 * PHP 5
 *
 * @category  DicFro
 * @package   Base
 * @author    Michel Corne <mcorne@yahoo.com>
 * @copyright 2008-2010 Michel Corne
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

require_once 'Base/View.php';
require_once 'Controller/Front.php';

/**
 * Runs the application
 *
 * @category  DicFro
 * @package   Base
 * @author    Michel Corne <mcorne@yahoo.com>
 * @copyright 2008-2010 Michel Corne
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class Base_Application
{
    /**
     * The name of the file containing the configuration directives
     * @var string
     */
    public $configFile = null;

    /**
     * Constructor
     *
     * @param  string $configFile the name of the file containing the configuration directives
     * @return void
     */
    public function __construct($configFile)
    {
        $this->configFile = $configFile;
    }

    /**
     * Runs the application
     *
     * @return void
     */
    public function run()
    {
        // reads the configuration directives
        $config = require $this->configFile;
        
        // creates the view and the front controller
        $view = new Base_View($config);
        $front = new Controller_Front($config, $view);
        
        // runs the front controller and renders the view
        $front->run();
        $view->render();
    }
}
