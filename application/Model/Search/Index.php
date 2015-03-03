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
    public function __construct($dataDir, $properties, $query = array(), $dictionaryDir = null)
    {
        if (! isset($query['class'])) {
            $query['class'] = 'Model_Query_Index';
        }

        parent::__construct($dataDir, $properties, $query, $dictionaryDir);
    }

    public function __call($name, $arguments)
    {
        if (! method_exists($this->query, $name)) {
            throw new Exception('invalid method');
        }

        $result = call_user_func_array(array($this->query, $name), $arguments);

        if (is_array($this->url)) {
            // selects the volume url
            $url = $this->url[$result['volume']];
        } else {
            // selects the dictionary url
            $url = $this->url;
        }

        if (! empty($result['fix'])) {
            // selects the page url, eg page missing in current volume, url points to this page in another volume as a fix
            $externalDict = $result['fix'];
        } else {
            // builds the page url
            $externalDict = sprintf($url, $result['image']);
        }

        $data =  array(
            'externalDict' => $externalDict,
            'page'         => $result['page'],
            'volume'       => $result['volume'],
        );

        if (empty($this->entries)) {
            $data['firstWord'] = $result['original'];
        } else {
            $data['entries'] = array(
                'current'  => $this->query->searchCurrentEntries($result['volume'], $result['page']),
                'previous' => $this->query->searchPreviousEntries($result['volume'], $result['page']),
                'next'     => $this->query->searchNextEntries($result['volume'], $result['page']),
            );
        }

        return $data;
    }
}