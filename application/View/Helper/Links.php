<?php
/**
 * Dicfro
 *
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2017 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

require_once 'View/Helper/Base.php';

class View_Helper_Links extends View_Helper_Base
{
    /**
     * @param bool $isEntries
     * @return string
     */
    public function setGoPageLink($isEntries = false)
    {
        if (! isset($this->gotoPageLink[$isEntries])) {
            if (empty($isEntries)) {
                if (empty($this->view->dictionary['volume'])) {
                    $this->gotoPageLink[$isEntries] = $this->setLink('page', $this->view->dictionary['url'], '%s', $this->view->word);
                } else {
                    $this->gotoPageLink[$isEntries] = $this->setLink('page', $this->view->dictionary['url'], '%s', '%s', $this->view->word);
                }

            } else {
                if (empty($this->view->dictionary['volume'])) {
                    $this->gotoPageLink[$isEntries] = $this->setLink('page', $this->view->dictionary['url'], '%s', '%s', $this->view->word);
                } else {
                    $this->gotoPageLink[$isEntries] = $this->setLink('page', $this->view->dictionary['url'], '%s', '%s', '%s', $this->view->word);
                }
            }
        }

        return $this->gotoPageLink[$isEntries];
    }

    /**
     * @params func_get_args()
     * @return string
     */
    public function setLink()
    {
        $arguments = func_get_args();
        array_unshift($arguments, $this->view->baseUrl);
        $arguments = array_filter($arguments);

        return implode('/', $arguments);
    }

    /**
     * @return string
     */
    public function setWordLink()
    {
        return $this->setLink('search', $this->view->dictionary['url'], '%s');
    }
}
