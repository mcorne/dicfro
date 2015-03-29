<?php
/**
 * Dicfro
 *
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2015 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

require_once 'Base/String.php';
require_once 'Base/View.php';

abstract class View_Helper_Base
{
    /**
     * @var Base_View
     */
    public $view;

    /**
     * @var Base_String
     */
    public $string;

    /**
     * @param Base_View $view
     */
    public function __construct(Base_View $view)
    {
        $this->view = $view;
        $this->string = new Base_String();
        $this->init();
    }

    public function init()
    {}
}
