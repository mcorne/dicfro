<?php

/**
 * Dicfro
 *
 * PHP 5
 *
 * @category  DicFro
 * @package   Config
 * @author    Michel Corne <mcorne@yahoo.com>
 * @copyright 2015 Michel Corne
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

/**
 * Test cases
 */

return [
    'century-dictionary' => [
        'availability-test' => [
            1 => '027',
            2 => '012',
            3 => '012',
            4 => '012',
            5 => '012',
            6 => '012',
            7 => '012',
            8 => '012',
        ],
        'page-test' => [
            ['action' => 'goToPage'        , 'page' => 0],

            ['action' => 'goToPage'        , 'page' => 1],
            ['action' => 'goToPage'        , 'page' => 1000],
            ['action' => 'goToPage'        , 'page' => 7046],
            ['action' => 'goToPage'        , 'page' => 10000],

            ['action' => 'goToNextPage'    , 'page' => 1],
            ['action' => 'goToNextPage'    , 'page' => 7046],
            ['action' => 'goToPreviousPage', 'page' => 1],
            ['action' => 'goToPreviousPage', 'page' => 7046],
        ],
        'search-test' => [
            1 => 'A',
            2 => 'Celticize',
            3 => 'Droop',
            4 => 'H',
            5 => 'M',
            6 => 'Pharmacological',
            7 => 'Salsify',
            8 => 'Technicality',
        ],
    ],

    'century-supplement' => [
        'availability-test' => [
            11 => '018',
            12 => '015',
        ],
        'page-test' => [
            ['action' => 'goToPage'        , 'page' => 0],

            ['action' => 'goToPage'        , 'page' => 1],
            ['action' => 'goToPage'        , 'page' => 1000],
            ['action' => 'goToPage'        , 'page' => 1467],
            ['action' => 'goToPage'        , 'page' => 10000],

            ['action' => 'goToNextPage'    , 'page' => 1],
            ['action' => 'goToNextPage'    , 'page' => 1467],
            ['action' => 'goToPreviousPage', 'page' => 1],
            ['action' => 'goToPreviousPage', 'page' => 1467],
        ],
        'search-test' => [
            11 => 'A',
            12 => 'M',
        ],
    ],

    'chambers-encyclopedia' => [
        'availability-test' => [
            1 => '023', // first page = 021
            2 => '010', // first page = 009
        ],
        'page-test' => [
            ['action' => 'goToPage'        , 'page' => 0     , 'volume' => 1],

            ['action' => 'goToPage'        , 'page' => 9     , 'volume' => 1],
            ['action' => 'goToPage'        , 'page' => 105   , 'volume' => 1],
            ['action' => 'goToPage'        , 'page' => 824   , 'volume' => 1],
            ['action' => 'goToPage'        , 'page' => 10000 , 'volume' => 1],
            ['action' => 'goToNextPage'    , 'page' => 9     , 'volume' => 1],
            ['action' => 'goToNextPage'    , 'page' => 824   , 'volume' => 1],
            ['action' => 'goToPreviousPage', 'page' => 9     , 'volume' => 1],
            ['action' => 'goToPreviousPage', 'page' => 824   , 'volume' => 1],

            ['action' => 'goToPage'        , 'page' => 1     , 'volume' => 2],
            ['action' => 'goToPage'        , 'page' => 100   , 'volume' => 2],
            ['action' => 'goToPage'        , 'page' => 828   , 'volume' => 2],
            ['action' => 'goToPage'        , 'page' => 10000 , 'volume' => 2],
            ['action' => 'goToNextPage'    , 'page' => 1     , 'volume' => 2],
            ['action' => 'goToNextPage'    , 'page' => 828   , 'volume' => 2],
            ['action' => 'goToPreviousPage', 'page' => 1     , 'volume' => 2],
            ['action' => 'goToPreviousPage', 'page' => 828   , 'volume' => 2],
        ],
        'search-test' => [
            1  => 'A',
            2  => 'Beaugency',
            // 3  => 'Catarrh',
            // 4  => 'Dionysius',
            // 5  => 'Friday',
            // 6  => 'Humber',
            // 7  => 'Maltebrun',
            // 8  => 'Peasant',
            // 9  => 'Round',
            // 10 => 'Swastika',
        ],
    ],

    'chanson-de-roland-glossary' => [
        'availability-test' => 'acuminier',
        'page-test' => [
            ['action' => 'goToPage'        , 'page' => 0],

            ['action' => 'goToPage'        , 'page' => 323],
            ['action' => 'goToPage'        , 'page' => 400],
            ['action' => 'goToPage'        , 'page' => 501],
            ['action' => 'goToPage'        , 'page' => 10000],

            ['action' => 'goToNextPage'    , 'page' => 323],
            ['action' => 'goToNextPage'    , 'page' => 501],
            ['action' => 'goToPreviousPage', 'page' => 323],
            ['action' => 'goToPreviousPage', 'page' => 501],
        ],
        'search-test' => 'a',
    ],

    'chretien-de-troyes-glossary' => [
        'availability-test' => 'abonder',
        'search-test'       => 'a',
    ],

    'conjugueur' => [
        'availability-test' => 'avoir',
        'search-test'       => 'avoir',
    ],

    'cotgrave' => [
        'availability-test' => 'abb',
        'search-test'       => 'abb',
    ],

    'couronnement-de-louis-glossary' => [
        'availability-test' => 'acorcié',
        'search-test'       => 'a',
    ],

    'dmf' => [
        'availability-test' => 'avoir',
        'search-test'       => 'avoir',
    ],

    'ducange' => [
        'availability-test' => 'abaces',
        'search-test'       => 'abaces',
    ],

    'dvlf' => [
        'availability-test' => 'avoir',
        'search-test'       => 'avoir',
    ],

    'encyclopedie-larousse' => [
        'availability-test' => [
            1  => '8',
            2  => '2',
            3  => '2',
            4  => '2',
            5  => '2',
            6  => '2',
            7  => '2',
            8  => '2',
            9  => '2',
            10 => '2',
            11 => '2',
            12 => '2',
            13 => '2',
            14 => '2',
            15 => '2',
            16 => '2',
            17 => '2',
            18 => '2',
            19 => '2',
            20 => '2',
        ],
        'search-test' => [
            1 => 'Aalto',
            2 => 'Amiens',
            3 => 'Australie',
            4 => 'Boudin',
            5 => 'Cétacés',
            6 => 'Compresseur',
            7 => 'Désinfection',
            8 => 'Épilepsie',
            9 => 'France',
            10 => 'Guesde',
            11 => 'Initiation',
            12 => 'La Pérouse',
            13 => 'Marconi',
            14 => 'Moyen âge',
            15 => 'Ostrava',
            16 => 'Plomb',
            17 => 'Renan',
            18 => 'scintigraphie', // 'Science-fiction' is cut at the dash,
            19 => 'Syndrome',
            20 => 'Tuyau',
        ],
    ],

    'etymonline' => [
        'availability-test' => 'avid',
        'search-test'       => 'avid',
    ],

    'gaffiot-dictionary' => [
        'availability-test' => 'abaris',
        'search-test'       => 'a',
    ],

    'godefroy-dictionary' => [
        'availability-test' => 'aardoir',
        'search-test'       => 'a',
    ],

    'godefroy-lexicon' => [
        'availability-test' => 'acorsage',
        'search-test'       => 'a',
    ],

    'godefroy-supplement' => [
        'availability-test' => 'abevreur',
        'search-test'       => 'a',
    ],

    'grand-larousse' => [
        'availability-test' => [
            1  => '105.highres',
            2  => '9.highres',
            3  => '9.highres',
            4  => '9.highres',
            5  => '9.highres',
            6  => '9.highres',
            7  => '9.highres',
        ],
        'search-test' => [
            1 => 'a',
            2 => 'cirage',
            3 => 'ès',
            4 => 'indatable',
            5 => 'o',
            6 => 'psoas',
            7 => 'sus',
        ],
    ],

    'jeanneau' => [
        'availability-test' => 'Dico-a01.html#',
        'search-test'       => 'a',
    ],

    'lacurne-dictionary' => [
        'availability-test' => 'aatisson',
        'search-test'       => 'a',
    ],

    'littre' => [
        'availability-test' => 'avoir',
        'search-test'       => 'avoir',
    ],

    'orthonet' => [
        'availability-test' => 'avoir',
        'search-test'       => 'avoir',
    ],

    'petit-larousse' => [
        'availability-test' => [
            1 => '015', // first page = 014
            2 => '001', // first page = 000
            3 => '001', // first page = 000
            4 => '001', // first page = 000
        ],
        'search-test' => [
            1 => 'A',
            2 => 'D',
            3 => 'L',
            4 => 'R',
        ],
    ],

    'petit-larousse-np' => [
        'availability-test' => [
            5 => '000',
            6 => '000',
        ],
        'search-test' => [
            5 => 'A',
            6 => 'K',
        ],
    ],

    'roman-de-la-rose-glossary' => [
        'availability-test' => 'aaise',
        'search-test'       => 'a',
    ],

    'roman-de-renart-fhs-glossary' => [
        'availability-test' => 'aramir',
        'search-test'       => 'a',
    ],

    'roman-de-renart-meon-1-glossary' => [
        'availability-test' => 'acorde',
        'search-test'       => 'a',
    ],

    'roman-de-renart-meon-2-glossary' => [
        'availability-test' => 'adolé',
        'search-test'       => 'a',
    ],

    'roman-de-renart-meon-3-glossary' => [
        'availability-test' => 'aignel',
        'search-test'       => 'a',
    ],

    'roman-de-renart-meon-4-glossary' => [
        'availability-test' => 'acroire',
        'search-test'       => 'a',
    ],

    'roman-de-tristan-glossary' => [
        'availability-test' => 'acorder',
        'search-test'       => 'a',
    ],

    'roman-lexicon' => [
        'availability-test' => 'abat',
        'search-test'       => 'a',
    ],

    'tcaf' => [
        'availability-test' => 'pris',
        'search-test'       => 'pris',
    ],

    'tlfi' => [
        'availability-test' => 'avoir',
        'search-test'       => 'avoir',
    ],

    'vandaele-dictionary' => [
        'availability-test' => 'aan',
        'search-test'       => 'a',
    ],

    'whitaker' => [
        'availability-test' => 'A',
        'search-test'       => 'a',
    ],

    'webster' => [
        'availability-test' => 'avid',
        'search-test'       => 'avid',
    ],

    'wiktionary' => [
        'availability-test' => 'avid',
        'search-test'       => 'avid',
    ],

    'wiktionnaire' => [
        'availability-test' => 'avoir',
        'search-test'       => 'avoir',
    ],
];
