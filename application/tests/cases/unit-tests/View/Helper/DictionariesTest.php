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
            'properties' => ['view->config' => ['dictionaries' => [
                'century'  => [],
                'chambers' => [],
                'godefroy' => [],
            ]]],
        ],
        [
            'method'     => 'countDictionariesByLanguage',
            'properties' => ['view->config' => ['callback' => 'setConfig']],
        ],
        [
            'method'     => 'countDictionariesByType',
            'properties' => ['view->config' => ['dictionaries' => [
                'century'  => ['type' => 'index'],
                'chambers' => ['type' => 'index'],
                'godefroy' => ['type' => 'internal'],
            ]]],
        ],
        [
            'method'     => 'getDictionaryDescription',
            'properties' => ['view->dictionary' => ['description' => 'this is the Century...']],
            'comment'    => 'has description',
        ],
        [
            'method'     => 'getDictionaryDescription',
            'comment'    => 'no description',
        ],
        [
            'method'     => 'getNewDictionaries',
            'properties' => ['view->config' => ['dictionaries' => [ 'century' => [
                'created'     => '2000-01-01',
            ]]]],
            'comment'    => 'not new',
        ],
        [
            'method'     => 'getNewDictionaries',
            'properties' => ['view->config' => ['dictionaries' => [ 'chambers' => [
                'created'     => '2030-01-01',
                'name'        => 'Chambers',
                'description' => 'this is the Chambers...',
                'type'        => 'index',
            ]]]],
            'comment'    => 'new addition',
        ],
        [
            'method'     => 'getNewDictionaries',
            'properties' => ['view->config' => ['dictionaries' => [ 'godefroy' => [
                'created'     => '2000-01-01',
                'name'        => 'Godefroy',
                'description' => 'this is the Godefroy...',
                'type'        => 'internal',
                'updated'     => '2030-01-01',
                'url'         => 'http://godefroy.com',
            ]]]],
            'comment'    => 'new update',
        ],
        [
            'method'     => 'getPageTitle',
            'properties' => ['view->dictionary' => ['title' => 'Century']],
        ],
        [
            'method'     => 'getPageTitle',
            'properties' => ['view->dictionary' => ['name' => 'Chambers']],
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
