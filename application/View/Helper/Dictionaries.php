<?php
/**
 * Dicfro
 *
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2015 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

require_once 'View/Helper/Base.php';

class View_Helper_Dictionaries extends View_Helper_Base
{
    /**
     * @return int
     */
    public function countDictionaries()
    {
       return count($this->view->config['dictionaries']);
    }

    /**
     *
     * @return int
     */
    public function countDictionariesByLanguage()
    {
        $count = [];

        foreach($this->view->config['groups'] as $group) {
            $language = $group['language'];
            $count[$language] = count($group['dictionaries']);
        }

        return $count;
    }

    /**
     * @return int
     */
    public function countDictionariesByType()
    {
        $count = [];

        foreach($this->view->config['dictionaries'] as $dictionary) {
            $type = $dictionary['type'];
            isset($count[$type]) or $count[$type] = 0;
            $count[$type]++;
        }

        return $count;
    }

    /**
     * @return string
     */
    public function getDictionaryDescription()
    {
        if (! isset($this->view->dictionary['description'])) {
            return null;
        }

        return $this->view->dictionary['description'];
    }

    /**
     * @return array
     */
    public function getNewDictionaries()
    {
        date_default_timezone_set('UTC');
        $options = [];

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

                $options[] = [
                    'text'  => $dictionary['name'],
                    'title' => $dictionary['description'],
                    'type'  => $dictionary['type'],
                    'value' => $value,
                ];
            }
        }

        return $options;
    }

    /**
     * @return string
     */
    public function getPageTitle()
    {
        if (isset($this->view->dictionary['title'])) {
            $pageTitle = $this->view->dictionary['title'];
        } else {
            $pageTitle = $this->view->dictionary['name'];
        }

        return $pageTitle;
    }

    /**
     * @param string $selected
     * @param bool $english
     * @return array
     */
    public function groupDictionaries($selected, $english = false)
    {
        $dictionaries = $this->view->config['dictionaries'];
        $optgroups = [];

        foreach($this->view->config['groups'] as $group) {
            $options = [];

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

                $options[] = [
                    'list-title' => $listTitle,
                    'selected'   => $id == $selected or $value == $selected,
                    'text'       => $dictionary['name'],
                    'title'      => strip_tags($title),
                    'type'       => $dictionary['type'],
                    'value'      => $value,
                ];
            }

            $label = $this->view->languages[ $group['language'] ];

            $optgroups[] = [
                'label'      => $english ? $label['english']  : $label['original'],
                'language'   => $group['language'],
                'list-title' => $english ? $label['original'] : $label['english'],
                'options'    => $options,
            ];
        }

        return $optgroups;
    }
}
