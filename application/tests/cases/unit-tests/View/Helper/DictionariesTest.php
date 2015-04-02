<?php
/**
 * Dicfro
 *
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2015 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

require_once 'Base/View.php';
require_once 'View/Helper/Dictionaries.php';
require_once 'Model/UnitTest.php';

class DictionariesTest extends Model_UnitTest
{
    public $class = 'View_Helper_Dictionaries';

    public $testCases = [
        [
            'method'     => 'countDictionaries',
            'status'     => 'not-tested',
        ],
        [
            'method'     => 'countDictionariesByLanguage',
            'status'     => 'not-tested',
        ],
        [
            'method'     => 'countDictionariesByType',
            'status'     => 'not-tested',
        ],
        [
            'method'     => 'getDictionaryDescription',
            'properties' => ['view->dictionary' => ['description' => 'abc'] ],
            'comment'    => 'has description',
        ],
        [
            'method'     => 'getDictionaryDescription',
            'comment'    => 'no description',
        ],
        [
            'method'     => 'getNewDictionaries',
            'status'     => 'not-tested',
        ],
        [
            'method'     => 'getPageTitle',
            'status'     => 'not-tested',
        ],
        [
            'method'     => 'groupDictionaries',
            'args'       => 'chambers',
            'properties' => [
                'view->config'    => ['callback' => 'setConfig'],
                'view->languages' => ['callback' => 'setLanguages'],
            ],
            'comment'    => 'original language',
        ],
        [
            'method'     => 'groupDictionaries',
            'args'       => ['godefroy', true],
            'properties' => [
                'view->config'    => ['callback' => 'setConfig'],
                'view->languages' => ['callback' => 'setLanguages'],
            ],
            'comment'    => 'in english',
        ],
    ];

    public function setConfig()
    {
        return [
            'groups' => [
                 [
                     'dictionaries' => [
                         'century',
                         'chambers',
                     ],
                     'language' => 'en',
                     ],
                 [
                     'dictionaries' => [
                         'godefroy',
                     ],
                     'language' => 'fr',
                 ],
            ],

            'dictionaries' => [
                'century' => [
                    'description'    => 'this is the Century...',
                    'name'           => 'Century',
                    'type'           => 'index',
                ],
                'chambers' => [
                    'name'           => 'Chambers',
                    'description'    => 'this is the Chambers...',
                    'type'           => 'external',
                    'url'            => 'http://century.com',
                ],
                'godefroy' => [
                    'name'           => 'Godefroy',
                    'description'    => 'voici le Godefroy...',
                    'description-en' => 'this is the Godefroy',
                    'type'           => 'internal',
                ],
            ],
        ];
    }

    public function setLanguages()
    {
        return [
            'en'  => ['english' => 'English', 'original' => 'English'],
            'fr'  => ['english' => 'French' , 'original' => 'Français'],
        ];
    }

    public function setObject()
    {
        $view = new Base_View([], false);
        $object = new View_Helper_Dictionaries($view);

        return $object;
    }
}
