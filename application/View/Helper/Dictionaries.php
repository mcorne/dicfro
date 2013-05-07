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
 * Dictionaries View Helper
 *
 * @category   DicFro
 * @package    View
 * @subpackage Helper
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2013 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class View_Helper_Dictionaries extends View_Helper_Base
{
    /**
     * Counts all dictionaries
     */
    public function countDictionaries()
    {
       return count($this->view->config['dictionaries']);
    }

    /**
     * Counts dictionaries by language
     */
    public function countDictionariesByLanguage()
    {
        $count = array();

        foreach($this->view->config['groups'] as $group) {
            $language = $group['language'];
            $count[$language] = count($group['dictionaries']);
        }

        return $count;
    }

    /**
     * Counts dictionaries by type
     */
    public function countDictionariesByType()
    {
        $count = array();

        foreach($this->view->config['dictionaries'] as $dictionary) {
            $type = $dictionary['type'];
            isset($count[$type]) or $count[$type] = 0;
            $count[$type]++;
        }

        return $count;
    }

    /**
     * Returns the dictionary description
     *
     * @return mixed the dictionary description
     */
    public function getDescription()
    {
        return isset($this->view->dictionary['description']) ? $this->view->dictionary['description'] : '';
    }

    /**
     * Returns the groups of dictionaries for use in a select box
     *
     * @param string $selected
     * @param bool $english
     * @return array the groups of dictionaries
     */
    public function getGroups($selected, $english = false)
    {
        $dictionaries = $this->view->config['dictionaries'];
        $optgroups = array();

        foreach($this->view->config['groups'] as $group) {
            $options = array();

            foreach($group['dictionaries'] as $id) {
                $dictionary = $dictionaries[$id];

                if (isset($dictionary['url'])) {
                    $value = $dictionary['url'];
                } else {
                    $value =  $id;
                }

                $englishTitle = isset($dictionary['description-en']) ? $dictionary['description-en'] : null;

                if ($english) {
                    $title     = $englishTitle ? $englishTitle : $dictionary['description'];
                    $listTitle = $englishTitle ? $dictionary['description'] :  null;
                } else {
                    $title     = $dictionary['description'];
                    $listTitle = $englishTitle;
                }

                $options[] = array(
                    'list-title' => $listTitle,
                    'selected'   => $id == $selected or $value == $selected,
                    'text'       => $dictionary['name'],
                    'title'      => $title,
                    'type'       => $dictionary['type'],
                    'value'      => $value,
                );
            }

            $label = $this->view->languages[ $group['language'] ];

            $optgroups[] = array(
                'label'      => $english ? $label['english']  : $label['original'],
                'language'   => $group['language'],
                'list-title' => $english ? $label['original'] : $label['english'],
                'options'    => $options,
            );
        }

        return $optgroups;
    }

    /**
     * Returns the recently added dictionaries
     *
     * @return array
     */
    public function getNewDictionaries()
    {
        $options = array();

        foreach($this->view->config['dictionaries'] as $id => $dictionary) {
            $date = isset($dictionary['updated']) ? $dictionary['updated'] : $dictionary['created'];
            list($year, $month, $day) = explode('-', $date);
            $time = time() - 30 * 24 * 3600;

            if (mktime(0, 0, 0, $month, $day, $year) >= $time) {
                // dictionary was added less than 30 days ago
                if (isset($dictionary['url'])) {
                    $value = $dictionary['url'];
                } else {
                    $value =  $id;
                }

                $options[] = array(
                    'text'     => $dictionary['name'],
                    'title'    => $dictionary['description'],
                    'value'    => $value,
                );
            }
        }

        return $options;
    }

    /**
     * Returns the page title
     *
     * @return string the page title
     */
    public function getPageTitle()
    {
        return isset($this->view->dictionary['title']) ? $this->view->dictionary['title'] : $this->view->dictionary['name'];
    }
}