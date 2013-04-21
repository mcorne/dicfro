<?php

/**
 * Dicfro
 *
 * PHP 5
 *
 * @category   DicFro
 * @package    View
 * @subpackage Helper
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2013 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

require_once 'Base/String.php';
require_once 'Base/View.php';

/**
 * View Helper base class
 *
 * @category   DicFro
 * @package    View
 * @subpackage Helper
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2013 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

abstract class View_Helper_Base
{
    /**
     * View object
     * @var Base_View
     */
    public $view;

    /**
     * String object
     * @var Base_String
     */
    public $string;

    /**
     * Constructor
     *
     * @param  object $view the view object
     * @return void
     */
    public function __construct(Base_View $view)
    {
        $this->view = $view;
        $this->string = new Base_String;
        $this->init();
    }

    /**
     * Initialization
     */
    public function init()
    {
    }
}