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
 * @copyright  2008-2010 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

require_once 'View/Helper/Base.php';

/**
 * Images View Helper
 *
 * @category   DicFro
 * @package    View
 * @subpackage Helper
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2010 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class View_Helper_Images extends View_Helper_Base
{
    /**
     * Image information
     * @var array
     */
    public $images = array(
        // dicfro home and default
        'default' => array(
            'file'   => 'dicfro.jpg',
            'source' => 'http://farm2.static.flickr.com/1147/533118282_dcd53c68cb.jpg',
        ),
         // information pages
        'about' => array(
            'file'   => 'about.jpg',
            'source' => '',
        ),
        'dictionaries' => array(
            'file'   => 'dictionary-list.jpg',
            'source' => '',
        ),
        'help' => array(
            'file'   => 'help.jpg',
            'source' => 'http://www.cclspompano.com/448894_82304673.jpg',
        ),
        'options' => array(
            'file'   => 'options.jpg',
            'source' => '',
        ),
    );

    /**
     * Returns the image name
     *
     * @return string the image name
     */
    public function getImage()
    {
        if ($this->view->information) {
            $name = basename($this->view->information, '.phtml');
            if (isset($this->images[$name])) {
                $image = $this->images[$name];
            } else {
                $image = $this->images['default'];
            }

        } else {
            if (isset($this->view->dictionary['file'])) {
                $image = $this->view->dictionary;
            } else {
                $image = $this->images['default'];
            }
        }

       return $image;
    }

    /**
     * Returns the image file name
     *
     * @return string the image pathname
     */
    public function getImageFile()
    {
        $image = $this->getImage();

        return $this->view->setLinkUrl("img/pages/{$image['file']}");
    }
}