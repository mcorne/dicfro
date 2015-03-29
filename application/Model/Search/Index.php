<?php
/**
 * Dicfro
 *
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2015 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

require_once 'Model/Search.php';

/**
 * Searches an indexed dictionary
 */
class Model_Search_Index extends Model_Search
{
    public $queryClass = 'Model_Query_Index';

    /**
     * @param string $name
     * @param array $arguments
     * @return array
     * @throws Exception
     */
    public function __call($name, $arguments)
    {
        if (! method_exists($this->query, $name)) {
            throw new Exception('invalid method');
        }

        $result = call_user_func_array([$this->query, $name], $arguments);

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

        $data =  [
            'externalDict' => $externalDict,
            'page'         => $result['page'],
            'volume'       => $result['volume'],
        ];

        if (empty($this->entries)) {
            $data['firstWord'] = $result['original'];
        } else {
            $data['entries'] = [
                'current'  => $this->query->searchCurrentEntries($result['volume'], $result['page']),
                'previous' => $this->query->searchPreviousEntries($result['volume'], $result['page']),
                'next'     => $this->query->searchNextEntries($result['volume'], $result['page']),
            ];
        }

        return $data;
    }
}
