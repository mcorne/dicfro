<?php
/**
 * Dicfro
 *
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2015 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

require_once 'Base/View.php';
require_once 'View/Helper/Entries.php';
require_once 'Model/UnitTest.php';

class EntriesTest extends Model_UnitTest
{
    public $class = 'View_Helper_Entries';

    public $testCases = [
        [
            'method' => 'calculate_entry_hash',
            'args'   => 'def',
        ],
        [
            'method' => 'getEntries',
            'status' => 'not-tested',
        ],
        [
            'method' => 'getEntries',
            'status' => 'not-tested',
        ],
        [
            'method' => 'getEntries',
            'status' => 'not-tested',
        ],
        [
            'method' => 'getEntries',
            'status' => 'not-tested',
        ],
        [
            'method'  => 'getSelectedAsciiWord',
            'args'    => [[], 'abc'],
            'comment' => 'no entries',
        ],
        [
            'method'  => 'getSelectedAsciiWord',
            'args'    => [
                [
                    ['ascii' => 'ABC'],
                    ['ascii' => 'DEF'],
                ],
                'xyz',
            ],
            'comment' => 'none selected',
        ],
        [
            'method'  => 'getSelectedAsciiWord',
            'args'    => [
                [
                    ['ascii' => 'ABC'],
                    ['ascii' => 'CA'],
                    ['ascii' => 'GHI'],
                ],
                'ça',
            ],
            'comment' => 'one selected',
        ],
        [
            'method' => 'getSelectedExactWord',
            'args'    => [[], 'abc'],
            'comment' => 'no entries',
        ],
        [
            'method' => 'getSelectedExactWord',
            'args'    => [
                [
                    ['original' => 'abc'],
                    ['original' => 'def'],
                ],
                'xyz',
            ],
            'comment' => 'none selected',
        ],
        [
            'method' => 'getSelectedExactWord',
            'args'    => [
                [
                    ['original' => 'abc'],
                    ['original' => 'one TWO ça'],
                    ['original' => 'def'],
                ],
                'ONE two ça',
            ],
            'comment' => 'one selected',
        ],
        [
            'method' => 'getSelectedHash',
            'args'    => [[], 'abc'],
            'comment' => 'no entries',
        ],
        [
            'method' => 'getSelectedHash',
            'args'    => [
                [
                    ['original' => 'abc'],
                    ['original' => 'def'],
                ],
                'xyz',
            ],
            'comment' => 'none selected',
        ],
        [
            'method' => 'getSelectedHash',
            'args'    => [
                [
                    ['original' => 'abc'],
                    ['original' => 'def'],
                    ['original' => 'ghi'],
                ],
                '214229345',
            ],
            'comment' => 'one selected',
        ],
        [
            'method' => 'getSelectedLikeWord',
            'args'    => [[], 'abc'],
            'comment' => 'no entries',
        ],
        [
            'method' => 'getSelectedLikeWord',
            'args'    => [
                [
                    ['ascii' => 'ABC'],
                    ['ascii' => 'DEF'],
                ],
                'xyz',
            ],
            'comment' => 'none selected',
        ],
        [
            'method' => 'getSelectedLikeWord',
            'args'    => [
                [
                    ['ascii' => 'ABC'],
                    ['ascii' => 'CARPET'],
                    ['ascii' => 'GHI'],
                ],
                'ça',
            ],
            'comment' => 'one selected',
        ],
        [
            'method' => 'getSelectedWord',
            'args'    => [
                [
                    ['original' => 'abc'],
                    ['original' => 'one TWO ça'],
                    ['original' => 'def'],
                ],
                'ONE two ça',
            ],
            'comment' => 'same word',
        ],
        [
            'method' => 'getSelectedWord',
            'args'    => [
                [
                    ['ascii' => 'ABC', 'original' => 'abc'],
                    ['ascii' => 'CA' , 'original' => 'ca'],
                    ['ascii' => 'DEF', 'original' => 'def'],
                ],
                'ça',
            ],
            'comment' => 'same ASCII',
        ],
        [
            'method' => 'getSelectedWord',
            'args'    => [
                [
                    ['ascii' => 'ABC'    , 'original' => 'abc'],
                    ['ascii' => 'CARPET' , 'original' => 'carpet'],
                    ['ascii' => 'DEF'    , 'original' => 'def'],
                ],
                'ça',
            ],
            'comment' => 'same begining',
        ],
        [
            'method' => 'getSelectedWord',
            'args'    => [
                [
                    ['ascii' => 'ABC'    , 'original' => 'abc'],
                    ['ascii' => 'DEF'    , 'original' => 'def'],
                ],
                'xyz',
            ],
            'comment' => 'none selected',
        ],
        [
            'method' => 'setOption',
            'args'   => [[
                'original' => 'def',
                'page'     => 123,
                'volume'   => 9,
            ]],
            'comment' => 'not selected',
        ],
        [
            'method' => 'setOption',
            'args'   => [
                [
                    'original' => 'def',
                    'page'     => 123,
                    'volume'   => 9,
                ],
                true,
            ],
            'comment' => 'selected',
        ],
        [
            'method' => 'setOptions',
            'args'   => [
                [
                    ['original' => 'abc', 'page' => 123, 'volume' => 8],
                    ['original' => 'def', 'page' => 456, 'volume' => 9],
                ],
            ],
            'comment' => 'new',
        ],
        [
            'method' => 'setOptions',
            'args'   => [
                [
                    ['original' => 'abc', 'page' => 123, 'volume' => 8],
                    ['original' => 'def', 'page' => 456, 'volume' => 9],
                ],
                [
                    ['selected' => null, 'text' => 'ijk', 'value' => '111/22/333333333'],
                    ['selected' => null, 'text' => 'lmn', 'value' => '444/55/666666666'],
                ],
                1,
            ],
            'comment' => 'added and selected',
        ],
    ];

    public function setObject()
    {
        $view = new Base_View([], false);
        $object = new View_Helper_Entries($view);

        return $object;
    }
}
