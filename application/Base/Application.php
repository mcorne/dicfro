<?php
/**
 * Dicfro
 *
 * @author    Michel Corne <mcorne@yahoo.com>
 * @copyright 2008-2015 Michel Corne
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

require_once 'Base/View.php';
require_once 'Controller/Front.php';

/*
 * Runs the application
 */
class Base_Application
{
    public function run($dictionaryDir, $domainSubpath)
    {
        $config = require __DIR__ . '/../config.php';
        $config['dictionary-dir'] = $dictionaryDir;
        $config['domain-subpath'] = $domainSubpath;

        $view = new Base_View($config);
        $front = new Controller_Front($config, $view);

        $front->run();
        $view->render();
    }
}
