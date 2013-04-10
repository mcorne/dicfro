<?php

/**
 * Dicfro
 *
 * PHP 5
 *
 * @category   DicFro
 * @package    Model
 * @subpackage Search
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2010 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

require_once 'Model/Search.php';
// query classes are included as needed

/**
 * Search an indexed dictionary
 *
 * @category   DicFro
 * @package    Model
 * @subpackage Search
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2013 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class Model_Search_Index extends Model_Search
{
    public function __construct($directory, $properties, $query = array())
    {
        if (! isset($query['class'])) {
            $query['class'] = 'Model_Query_Index';
        }

        parent::__construct($directory, $properties, $query);
    }

    public function __call($name, $arguments)
    {
        if (! method_exists($this->query, $name)) {
            throw new Exception('invalid method');
        }

        $result = call_user_func_array(array($this->query, $name), $arguments);

        if (is_array($this->url)) {
            $url = $this->url[$result['volume']];
        } else {
            $url = $this->url;
        }

        $data =  array(
            'externalDict' => sprintf($url, $result['image']),
            'page'         => $result['page'],
            'volume'       => $result['volume'],
        );

        if (empty($this->entries)) {
            $data['firstWord'] = $result['original'];
        } else {
            $data['entries'] = array(
                'current'  => $result,
                'previous' => $this->query->searchPreviousEntries($result['volume'], $result['page']),
                'next'     => $this->query->searchNextEntries($result['volume'], $result['page']),
            );
        }

        return $data;
    }
}