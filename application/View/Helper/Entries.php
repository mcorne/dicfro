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

require_once 'View/Helper/Base.php';

/**
 * Entries View Helper
 *
 * @category   DicFro
 * @package    View
 * @subpackage Helper
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2013 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class View_Helper_Entries extends View_Helper_Base
{
    /**
     * Returns the entries for use in a select box
     *
     * @return array
     */
    public function getEntries()
    {
        $options = array();

        foreach($this->view->entries['previous'] as $entry) {
            $options[] = $this->setOption($entry);
        }

        $entry = $this->view->entries['current'];
        $options[] = $this->setOption($entry, true);

        foreach($this->view->entries['next'] as $entry) {
            $options[] = $this->setOption($entry);
        }

        return $options;
    }

    /**
     * Sets the option
     *
     * @param array $entry
     * @return array
     */
    public function setOption($entry, $selected = false)
    {
        return array(
            'selected' => $selected,
            'text'     => empty($entry['original']) ? '--' : $entry['original'],
            'value'    => $entry['page'] . '/' . $entry['volume'],
        );
    }
}