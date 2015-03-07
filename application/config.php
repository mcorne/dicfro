<?php

/**
 * Dicfro
 *
 * PHP 5
 *
 * @category  DicFro
 * @package   Config
 * @author    Michel Corne <mcorne@yahoo.com>
 * @copyright 2008-2015 Michel Corne
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

/**
 * Application configuration file
 *
 * IMPORTANT! keep "domain-subpath" and "dictionary-dir" in sync with .htaccess
 */

$applicationDir = dirname(__FILE__); // global variable used by scripts

return array(
    // data directory containing indexes and databases
    'data-dir' => realpath("$applicationDir/data"),

    // default dictionary for each language
    'dictionary-defaults' => array(
        'en' => 'century',
        'fr' => 'gdf',
    ),

    // sets the dictionary directory containting images (dictionary pages)
    // this MUST BE kept in sync with .htaccess dictionary rewrite rule
    // eg "dicfro-dictionary/chretien/public/mImg/0000025.gif" in development
    'dictionary-dir' => dirname(__FILE__) . '/../../dicfro-dictionary/%s/public',
    // leave empty if dictionaries are in dicfro public/dictionary, eg "dictionary/chretien/mImg/0000025.gif" in production

    // dictionaries details
    'dictionaries' => array(
        'century' => array(
            'availability-test' => array(
                1 => '027',
                2 => '012',
                3 => '012',
                4 => '012',
                5 => '012',
                6 => '012',
                7 => '012',
                8 => '012',
            ),
            'created'      => '2013-04-14',
            'description'  => 'The Century dictionary, an encyclopedic lexicon of the English language, William D. Whitney, 1895',
            'image'        => 'century-dictionary.jpg',
            'introduction' => 'century-dictionary.phtml',
            'language'     => 'en',
            'name'         => 'Century Dictionary',
            'search'       => array(
                'properties' => array(
                    'url'     => array(
                        1 => 'http://ia601409.us.archive.org/BookReader/BookReaderImages.php?zip=/4/items/centurydict01whit/centurydict01whit_jp2.zip&file=centurydict01whit_jp2/centurydict01whit_0%03u.jp2&scale=3&rotate=0',
                        2 => 'http://ia700400.us.archive.org/BookReader/BookReaderImages.php?zip=/1/items/cu31924091890594/cu31924091890594_jp2.zip&file=cu31924091890594_jp2/cu31924091890594_0%03u.jp2&scale=3&rotate=0',
                        3 => 'http://ia700406.us.archive.org/BookReader/BookReaderImages.php?zip=/21/items/cu31924091890602/cu31924091890602_jp2.zip&file=cu31924091890602_jp2/cu31924091890602_0%03u.jp2&scale=3&rotate=0',
                        4 => 'http://ia700406.us.archive.org/BookReader/BookReaderImages.php?zip=/3/items/cu31924091890610/cu31924091890610_jp2.zip&file=cu31924091890610_jp2/cu31924091890610_0%03u.jp2&scale=3&rotate=0',
                        5 => 'http://ia600406.us.archive.org/BookReader/BookReaderImages.php?zip=/2/items/cu31924091890628/cu31924091890628_jp2.zip&file=cu31924091890628_jp2/cu31924091890628_0%03u.jp2&scale=3&rotate=0',
                        6 => 'http://ia700402.us.archive.org/BookReader/BookReaderImages.php?zip=/35/items/cu31924091890636/cu31924091890636_jp2.zip&file=cu31924091890636_jp2/cu31924091890636_0%03u.jp2&scale=3&rotate=0',
                        7 => 'http://ia600407.us.archive.org/BookReader/BookReaderImages.php?zip=/8/items/cu31924091890644/cu31924091890644_jp2.zip&file=cu31924091890644_jp2/cu31924091890644_0%03u.jp2&scale=3&rotate=0',
                        8 => 'http://ia600407.us.archive.org/BookReader/BookReaderImages.php?zip=/17/items/cu31924091890651/cu31924091890651_jp2.zip&file=cu31924091890651_jp2/cu31924091890651_0%03u.jp2&scale=3&rotate=0',
                    ),
                )
            ),
            'title'        => 'Century D.',
            'type'         => 'index',
            'url'          => 'century-dictionary',
            'volume'       => 'readonly',
        ),

        'century-supplement' => array(
            'availability-test' => array(
                11 => '018',
                12 => '015',
            ),
            'created'      => '2013-04-18',
            'description'  => 'The Century dictionary supplement, Benjamin E. Smith, 1909',
            'image'        => 'century-supplement.jpg',
            'language'     => 'en',
            'name'         => 'Century Supplement',
            'search'       => array(
                'properties' => array(
                    'url'     => array(
                        11 => 'http://ia700404.us.archive.org/BookReader/BookReaderImages.php?zip=/4/items/cu31924091890685/cu31924091890685_jp2.zip&file=cu31924091890685_jp2/cu31924091890685_0%03u.jp2&scale=3&rotate=0',
                        12 => 'http://ia902606.us.archive.org/BookReader/BookReaderImages.php?zip=/33/items/centurydictionar12whituoft/centurydictionar12whituoft_jp2.zip&file=centurydictionar12whituoft_jp2/centurydictionar12whituoft_0%03u.jp2&scale=3&rotate=0',
                    ),
                )
            ),
            'title'        => 'Century S.',
            'type'         => 'index',
            'volume'       => 'readonly',
        ),

        'chambers-encyclopedia' => array(
            'availability-test' => array(
                1  => '023', // first page = 021
                2  => '010', // first page = 009
            ),
            'created'      => '2014-05-01',
            'description'  => "The Chambers's encyclopaedia, a dictionary of universal knowledge, William & Robert Chambers, 1901",
            'image'        => 'chambers-encyclopedia.jpg',
            'introduction' => 'chambers-encyclopedia.phtml',
            'language'     => 'en',
            'name'         => "Chambers's Encyclopaedia (A-Ca)",
            'search'       => array(
                'properties' => array(
                    'url'     => array(
                        1  => 'https://ia801407.us.archive.org/BookReader/BookReaderImages.php?zip=/5/items/chamberssency01lond/chamberssency01lond_jp2.zip&file=chamberssency01lond_jp2/chamberssency01lond_0%03u.jp2&scale=3&rotate=0',
                        2  => 'https://ia601405.us.archive.org/BookReader/BookReaderImages.php?zip=/21/items/chamberssency02lond/chamberssency02lond_jp2.zip&file=chamberssency02lond_jp2/chamberssency02lond_0%03u.jp2&scale=3&rotate=0',
                        3  => 'https://ia600307.us.archive.org/BookReader/BookReaderImages.php?zip=/6/items/chamberssency03lond/chamberssency03lond_jp2.zip&file=chamberssency03lond_jp2/chamberssency03lond_0%03.jp2&scale=3&rotate=0',
                        4  => 'https://ia700302.us.archive.org/BookReader/BookReaderImages.php?zip=/7/items/chamberssency04lond/chamberssency04lond_jp2.zip&file=chamberssency04lond_jp2/chamberssency04lond_0%03.jp2&scale=3&rotate=0',
                        5  => 'https://ia700306.us.archive.org/BookReader/BookReaderImages.php?zip=/17/items/chamberssency05lond/chamberssency05lond_jp2.zip&file=chamberssency05lond_jp2/chamberssency05lond_0%03.jp2&scale=3&rotate=0',
                        6  => 'https://ia600300.us.archive.org/BookReader/BookReaderImages.php?zip=/18/items/chamberssency06lond/chamberssency06lond_jp2.zip&file=chamberssency06lond_jp2/chamberssency06lond_0%03.jp2&scale=3&rotate=0',
                        7  => 'https://ia700304.us.archive.org/BookReader/BookReaderImages.php?zip=/24/items/chamberssency07lond/chamberssency07lond_jp2.zip&file=chamberssency07lond_jp2/chamberssency07lond_0%03.jp2&scale=3&rotate=0',
                        8  => 'https://ia600308.us.archive.org/BookReader/BookReaderImages.php?zip=/25/items/chamberssency08lond/chamberssency08lond_jp2.zip&file=chamberssency08lond_jp2/chamberssency08lond_0%03.jp2&scale=3&rotate=0',
                        9  => 'https://ia600302.us.archive.org/BookReader/BookReaderImages.php?zip=/27/items/chambersencyclop09lond/chambersencyclop09lond_jp2.zip&file=chambersencyclop09lond_jp2/chambersencyclop09lond_0%03.jp2&scale=3&rotate=0',
                        10 => 'https://ia700306.us.archive.org/BookReader/BookReaderImages.php?zip=/17/items/chambersencyclop10lond/chambersencyclop10lond_jp2.zip&file=chambersencyclop10lond_jp2/chambersencyclop10lond_0%03.jp2&scale=3&rotate=0',
                    ),
                )
            ),
            'title'        => 'Chambers E.',
            'type'         => 'index',
            'updated'      => '2014-05-03',
            'volume'       => 'input',
        ),

        'chretien-de-troyes-glossary' => array(
            'availability-test' => 'abonder',
            'created'        => '2010-08-01',
            'description'    => 'Kristian von Troyes Wörterbuch zu seinem sämtlichen Werken, Wendelin Foerster, 1914',
            'description-en' => 'Dictionary of the works of Chrétien de Troyes, Wendelin Foerster, 1914',
            'image'          => 'dictionnaire-chretien-de-troyes.jpg',
            'language'       => 'fr',
            'name'           => 'Chrétien de Troyes',
            'parser'         => array(
                'class'      => 'Model_Parser_GaffiotLike',
                'properties' => array(
                    'lineTpl'    => '~^(.+?)__BR____BR__<@_(\d+).tif_>FoersterEdic__BR____BR__~',
                )
            ),
            'search'         => array(
                'properties' => array(
                    'needTcaf'   => true,
                    'needTobler' => true,
                )
            ),
            'title'          => 'Chrétien',
            'type'           => 'internal',
            'url'            => 'glossaire-chretien-de-troyes',
        ),

        'conjugueur' => array(
            'availability-test' => 'avoir',
            'created'           => '2013-07-20',
            'description'       => 'Le conjugeur de Conjugaison.net',
            'description-en'    => 'The Conjugaison.net conjugator',
            'introduction'      => 'http://www.conjugaison.net',
            'language'          => 'fr',
            'name'              => 'Conjugaison',
            'search'            => array(
                'properties'    => array(
                    'convert' => 'utf8toASCII', // iso-8859-1 site
                    'emptyWord' => 'http://www.conjugaison.net',
                    'suffix'    => '.html',
                    'url'       => 'http://www.conjugaison.net/fr/',
                )
            ),
            'type'              => 'external',
        ),

        'cotgrave' => array(
            'availability-test' => 'abb',
            'created'      => '2012-03-11',
            'description'  => 'A dictionarie of the French and English tongues, Randle Cotgrave, 1611',
            'introduction' => 'http://www.pbm.com/~lindahl/cotgrave/',
            'language'     => 'fr',
            'name'         => 'Cotgrave',
            'search'       => array(
                'class' => 'Model_Search_Cotgrave',
                'properties' => array(
                    'url' => 'http://www.pbm.com/~lindahl/cotgrave/search/search_backend.cgi?query=',
                ),
            ),
            'type'         => 'external',
        ),

        'couronnement-de-louis-glossary' => array(
            'availability-test' => 'acorcié',
            'created'        => '2010-08-01',
            'description'    => 'Glossaire du Couronnement de Louis, Ernest Langlois, 1888',
            'description-en' => 'Glossary of the Coronation of Louis, Ernest Langlois, 1888',
            'image'          => 'glossaire-couronnement-de-louis.jpg',
            'language'       => 'fr',
            'name'           => 'Couronnement de Louis',
            'parser'         => array(
                'class'      => 'Model_Parser_GaffiotLike',
                'properties' => array(
                    'imageNumberTpl' => '0000%s',
                    'lineTpl'        => '~^(.+?)__BR____BR__<@_tx(\d+).tif_>EGlos_CourLouisL1__BR____BR__~',
                )
            ),
            'search'         => array(
                'properties' => array(
                    'needTcaf'   => true,
                    'needTobler' => true,
                )
            ),
            'title'          => 'Couronnement',
            'type'           => 'internal',
            'url'            => 'glossaire-couronnement-de-louis',
        ),

        'dmf' => array(
            'availability-test' => 'avoir',
            'created'        => '2008-04-14',
            'description'    => "Dictionnaire du Moyen Français (DMF), Analyse et Traitement Informatique de la Langue Française (Atilf)",
            'description-en' => "Middle French dictionary (DMF), Computer Processing and Analysis of the French Language (Atilf)",
            'introduction'   => 'http://www.atilf.fr/dmf/',
            'language'       => 'fr',
            'name'           => 'Moyen français',
            'search'         => array(
                'properties' => array(
                    'emptyWord' => 'http://www.atilf.fr/dmf/',
                    'url'       => 'http://www.atilf.fr/dmf/definition/',
                )
            ),
            'title'          => 'DMF',
            'type'           => 'external',
        ),

        'ducange' => array(
            'availability-test' => 'abaces',
            'created'           => '2013-03-19',
            'description'       => 'Glossarium mediæ et infimæ latinitatis, Du Cange, 1678',
            'description-en'    => 'Glossary of medieval and late Latin, Du Cange, 1678',
            'introduction'      => 'http://ducange.enc.sorbonne.fr/',
            'language'          => 'la',
            'name'              => 'Du Cange',
            'search'            => 'http://ducange.enc.sorbonne.fr/',
            'title'             => 'Du Cange',
            'type'              => 'external',
        ),

        'dvlf' => array(
            'availability-test' => 'avoir',
            'created'        => '2012-06-03',
            'description'    => 'Dictionnaire vivant de la langue française (DVLF), The ARTFL Project',
            'description-en' => 'Live dictionary of the French language (DVLF), The ARTFL Project',
            'introduction'   => 'http://dvlf.uchicago.edu/',
            'language'       => 'fr',
            'name'           => 'Dict. vivant de la langue fr.',
            'search'         => array(
                'properties' => array(
                    'emptyWord' => 'http://dvlf.uchicago.edu/',
                    'url'       => 'http://dvlf.uchicago.edu/mot/',
                )
            ),
            'title'          => 'DVLF',
            'type'           => 'external',
        ),

        'encyclopedie-larousse' => array(
            'availability-test' => array(
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
            ),
            'created'        => '2013-04-03',
            'description'    => 'La Grande encyclopédie Larousse, 1971-1976',
            'description-en' => 'The Larousse great encyclopedia, 1971-1976',
            'image'          => 'encyclopedie-larousse.jpg',
            'language'       => 'fr',
            'name'           => 'Encyclopédie Larousse',
            'parser'         => array(
                'class'      => 'Model_Parser_Index',
                'properties' => array(
                    // replacements ex. "Saint-Aubin" => "SaintAubin", "La Pérouse" => "LaPérouse", etc.
                    'entryReplacements' => array('pattern' => '~^(Sainte?|La|néo|New|Nord|Pays|photo|sous|trans|van|Vi[èê]t)[ -]~iu', 'replacement' => '$1'),
                    'wordSeparator'     => '~[ -]~',
                )
            ),
            'search'         => array(
                'properties' => array(
                    'entries' => true,
                    'url'     => array(
                        1  => 'http://gallica.bnf.fr/ark:/12148/bpt6k1200512k/f%u.highres',
                        2  => 'http://gallica.bnf.fr/ark:/12148/bpt6k12005130/f%u.highres',
                        3  => 'http://gallica.bnf.fr/ark:/12148/bpt6k1200514d/f%u.highres',
                        4  => 'http://gallica.bnf.fr/ark:/12148/bpt6k1200515t/f%u.highres',
                        5  => 'http://gallica.bnf.fr/ark:/12148/bpt6k12005167/f%u.highres',
                        6  => 'http://gallica.bnf.fr/ark:/12148/bpt6k1200517n/f%u.highres',
                        7  => 'http://gallica.bnf.fr/ark:/12148/bpt6k12005182/f%u.highres',
                        8  => 'http://gallica.bnf.fr/ark:/12148/bpt6k1200519g/f%u.highres',
                        9  => 'http://gallica.bnf.fr/ark:/12148/bpt6k12005204/f%u.highres',
                        10 => 'http://gallica.bnf.fr/ark:/12148/bpt6k1200521j/f%u.highres',
                        11 => 'http://gallica.bnf.fr/ark:/12148/bpt6k1200522z/f%u.highres',
                        12 => 'http://gallica.bnf.fr/ark:/12148/bpt6k1200523c/f%u.highres',
                        13 => 'http://gallica.bnf.fr/ark:/12148/bpt6k1200524s/f%u.highres',
                        14 => 'http://gallica.bnf.fr/ark:/12148/bpt6k12005256/f%u.highres',
                        15 => 'http://gallica.bnf.fr/ark:/12148/bpt6k1200526m/f%u.highres',
                        16 => 'http://gallica.bnf.fr/ark:/12148/bpt6k12005271/f%u.highres',
                        17 => 'http://gallica.bnf.fr/ark:/12148/bpt6k1200528f/f%u.highres',
                        18 => 'http://gallica.bnf.fr/ark:/12148/bpt6k1200529v/f%u.highres',
                        19 => 'http://gallica.bnf.fr/ark:/12148/bpt6k1200530h/f%u.highres',
                        20 => 'http://gallica.bnf.fr/ark:/12148/bpt6k1200531x/f%u.highres',
                    ),
                )
            ),
            'title'          => 'E. Larousse',
            'type'           => 'index',
            'updated'        => '2013-09-28',
            'volume'         => 'readonly',
        ),

        'etymonline' => array(
            'availability-test' => 'avid',
            'created'           => '2014-04-09',
            'description'       => 'Online Etymology Dictionary',
            'introduction'      => 'http://www.etymonline.com/index.php?allowed_in_frame=1',
            'language'          => 'en',
            'name'              => 'Etymonline',
            'search'            => 'http://www.etymonline.com/index.php?allowed_in_frame=1&searchmode=none&search=',
            'type'              => 'external',
        ),

        'gaffiot-dictionary' => array(
            'availability-test' => 'abaris',
            'created'        => '2008-06-27',
            'description'    => 'Dictionnaire latin-français, Félix Gaffiot, 1934',
            'description-en' => 'Latin-French dictionary, Félix Gaffiot, 1934',
            'image'          => 'gaffiot.jpg',
            'language'       => 'la',
            'name'           => 'Gaffiot',
            'parser'         => array(
                'class'      => 'Model_Parser_GaffiotLike',
                'properties' => array(
                    'endWord' => '_abréviations',
                    'lineTpl' => '~^(.+?)__BR____BR__<@_tx(\d+).tif_>GaffiotEdic__BR____BR__~',
                )
            ),
            'search'         => array(
                'properties' => array(
                    'needWhitaker' => true,
                ),
            ),
            'type'           => 'internal',
        ),

        'gdf' => array(
            'availability-test' => 'aardoir',
            'created'        => '2008-04-14',
            'description'    => "Dictionnaire de l'ancienne langue française et de tous ses dialectes du IX<sup>e</sup> au XV<sup>e</sup> siècle, Frédéric Godefroy, 1880-1895",
            'description-en' => 'Dictionary of the Old French language and all its dialects from the ninth to fifteenth century, Frédéric Godefroy, 1880-1895',
            'image'          => 'dictionnaire-godefroy.jpg',
            'language'       => 'fr',
            'name'           => 'Godefroy / Dictionnaire',
            'parser'         => 'Model_Parser_GdfLike',
            'search'         => array(
                'properties' => array(
                    'errataFiles'      => 'gdf/mPimg-3/%s-%s[a-z]*/*.gif',
                    'imagePath'        => 'gdf/mImg/%s.gif',
                    'needErrataImages' => true,
                    'needGhostwords'   => true,
                    'needTcaf'         => true,
                    'needTobler'       => true,
                )
            ),
            'title'          => 'D. Godefroy',
            'type'           => 'internal',
            'url'            => 'dictionnaire-godefroy',
            'volume'         => 'input',
        ),

        'gdfc' => array(
            'availability-test' => 'abevreur',
            'created'        => '2008-04-14',
            'description'    => "Complément du dictionnaire de l'ancienne langue française et de tous ses dialectes du IX<sup>e</sup> au XV<sup>e</sup> siècle, Frédéric Godefroy, 1895-1902",
            'description-en' => 'Dictionary supplement of the Old French language and all its dialects from the ninth to fifteenth century, Frédéric Godefroy, 1880-1895',
            'image'          => 'complement-godefroy.jpg',
            'language'       => 'fr',
            'name'           => 'Godefroy / Complément',
            'parser'         => 'Model_Parser_GdfLike',
            'search'         => array(
                'properties' => array(
                    'digit'            => 1,
                    'errataFiles'      => 'gdfc/mPimg-3/%s-%s[a-z]*/*.gif',
                    'imagePath'        => 'gdfc/mImg/%s.gif',
                    'needErrataImages' => true,
                    'needGhostwords'   => true,
                    'needTcaf'         => true,
                    'needTobler'       => true,
                )
            ),
            'title'          => 'C. Godefroy',
            'type'           => 'internal',
            'url'            => 'complement-godefroy',
            'volume'         => 'input',
        ),

        'gdflex' => array(
            'availability-test' => 'acorsage',
            'created'        => '2008-04-14',
            'description'    => "Lexique de l'ancien français, Frédéric Godefroy, 1901",
            'description-en' => 'Old French lexicon, Frédéric Godefroy, 1880-1895',
            'image'          => 'lexique-godefroy.jpg',
            'language'       => 'fr',
            'name'           => 'Godefroy / Lexique',
            'search'         => array(
                'properties' => array(
                    'needErrataText' => true,
                    'needGhostwords' => true,
                    'needTcaf'       => true,
                    'needTobler'     => true,
                )
            ),
            'title'          => 'L. Godefroy',
            'type'           => 'internal',
            'url'            => 'lexique-godefroy',
        ),

        'grand-larousse' => array(
            'availability-test' => array(
                1  => '105.highres',
                2  => '9.highres',
                3  => '9.highres',
                4  => '9.highres',
                5  => '9.highres',
                6  => '9.highres',
                7  => '9.highres',
            ),
            'created'        => '2013-09-16',
            'description'    => 'Grand Larousse de la langue française, 1971-1978',
            'description-en' => 'The Grand Larousse of the French language, 1971-1978',
            'image'          => 'grand-larousse.jpg',
            'language'       => 'fr',
            'name'           => 'Grand Larousse',
            'parser'         => array(
                'class'      => 'Model_Parser_Index',
                'properties' => array(
                    'wordSeparator' => '~,~',
                )
            ),
            'search'         => array(
                'properties' => array(
                    'url' => array(
                        1  => 'http://gallica.bnf.fr/ark:/12148/bpt6k1200532b/f%u.highres',
                        2  => 'http://gallica.bnf.fr/ark:/12148/bpt6k1200533r/f%u.highres',
                        3  => 'http://gallica.bnf.fr/ark:/12148/bpt6k12005345/f%u.highres',
                        4  => 'http://gallica.bnf.fr/ark:/12148/bpt6k1200535k/f%u.highres',
                        5  => 'http://gallica.bnf.fr/ark:/12148/bpt6k1200551p/f%u.highres',
                        6  => 'http://gallica.bnf.fr/ark:/12148/bpt6k1200556r/f%u.highres',
                        7  => 'http://gallica.bnf.fr/ark:/12148/bpt6k12005590/f%u.highres',
                    ),
                )
            ),
            'title'          => 'G. Larousse',
            'type'           => 'index',
            'updated'        => '2013-09-20',
            'volume'         => 'readonly',
        ),

        'jeanneau' => array(
            'availability-test' => 'Dico-a01.html#',
            'created'        => '2008-06-27',
            'description'    => 'Dictionnaire français-latin, Gérard Jeanneau',
            'description-en' => 'Latin-French dictionary, Gérard Jeanneau',
            'introduction'   => 'http://www.prima-elementa.fr/Dico.htm',
            'language'       => 'la',
            'name'           => 'Jeanneau',
            'search'         => array(
                'class' => 'Model_Search_Jeanneau',
                'properties' => array(
                    'url' => 'http://www.prima-elementa.fr/',
                ),
            ),
            'type'           => 'external',
        ),

        'lacurne-dictionary' => array(
            'availability-test' => 'aatisson',
            'created'        => '2013-03-29',
            'description'    => "Dictionnaire historique de l'ancien langage françois ou glossaire de la langue françoise (LacEdic), J.B. de la Curne de Sainte-Palaye, 1875-1882",
            'description-en' => 'Historical dictionary of the Old French language (LacEdic), J.B. de la Curne de Sainte-Palaye, 1875-1882',
            'image'          => 'dictionnaire-lacurne.jpg',
            'language'       => 'fr',
            'name'           => 'Lacurne / Dictionnaire',
            'search'         => array(
                'properties' => array(
                    'needTcaf'   => true,
                    'needTobler' => true,
                )
            ),
            'title'          => 'LacEdic',
            'type'           => 'internal',
            'url'            => 'dictionnaire-lacurne',
            'volume'         => 'input',
        ),

        'littre' => array(
            'availability-test' => 'avoir',
            'created'           => '2010-08-01',
            'description'       => 'Dictionnaire de la langue française, Émile Littré, 1872-1877',
            'description-en'    => 'Dictionary of the French language, Émile Littré, 1872-1877',
            'introduction'      => 'http://www.littre.org',
            'language'          => 'fr',
            'name'              => 'Littré',
            'search'            => 'http://www.littre.org/definition/',
            'type'              => 'external',
            'updated'           => '2013-05-10',
        ),

        'orthonet' => array(
            'availability-test' => 'avoir',
            'created'           => '2013-07-13',
            'description'       => 'Orthonet, orthographe et grammaire de la langue française, Conseil international de la Langue française',
            'description-en'    => 'Orthonet, spelling and grammar of the French language, International Council of the French Language',
            'introduction'      => 'http://orthonet.sdv.fr/',
            'language'          => 'fr',
            'name'              => 'Orthonet',
            'search'            => 'http://orthonet.sdv.fr/php/rech_mot.php?mot=',
            'type'              => 'external',
        ),

        'petit-larousse' => array(
            'availability-test' => array(
                1 => '015', // first page = 014
                2 => '001', // first page = 000
                3 => '001', // first page = 000
                4 => '001', // first page = 000
            ),
            'created'        => '2013-04-10',
            'description'    => "Petit Larousse illustré (noms communs), nouveau dictionnaire encyclopédique (5e édition), Claude Augé, 1906",
            'description-en' => "Petit Larousse illustrated dictionary (common names), new encyclopedic dictionary (5th Edition), Claude Augé, 1906",
            'image'          => 'petit-larousse.jpg',
            'language'       => 'fr',
            'name'           => 'Petit Larousse / N. communs',
            'search'         => array(
                'properties' => array(
                    'url'     => array(
                        1 => 'http://ia700503.us.archive.org/BookReader/BookReaderImages.php?zip=/8/items/PetitLarousse19061/larousse_petit_1906_a_jp2.zip&file=larousse_petit_1906_a_jp2/larousse_petit_1906_a_0%03u.jp2&scale=3&rotate=0',
                        2 => 'http://ia700507.us.archive.org/BookReader/BookReaderImages.php?zip=/6/items/PetitLarousse19062/larousse_petit_1906_b_jp2.zip&file=larousse_petit_1906_b_jp2/larousse_petit_1906_b_0%03u.jp2&scale=3&rotate=0',
                        3 => 'http://ia600503.us.archive.org/BookReader/BookReaderImages.php?zip=/22/items/PetitLarousse19063/larousse_petit_1906_c_jp2.zip&file=larousse_petit_1906_c_jp2/larousse_petit_1906_c_0%03u.jp2&scale=3&rotate=0',
                        4 => 'http://ia700507.us.archive.org/BookReader/BookReaderImages.php?zip=/5/items/PetitLarousse19064/larousse_petit_1906_d_jp2.zip&file=larousse_petit_1906_d_jp2/larousse_petit_1906_d_0%03u.jp2&scale=3&rotate=0',
                    ),
                )
            ),
            'title'          => 'N.c. Larousse',
            'type'           => 'index',
            'volume'         => 'readonly',
        ),

        'petit-larousse-np' => array(
            'availability-test' => array(
                5 => '000',
                6 => '000',
            ),
            'created'        => '2013-04-13',
            'description'    => "Petit Larousse illustré (noms propres), nouveau dictionnaire encyclopédique (5e édition), Claude Augé, 1906",
            'description-en' => "Petit Larousse illustrated dictionary (proper names), new encyclopedic dictionary (5th Edition), Claude Augé, 1906",
            'image'          => 'petit-larousse-np.jpg',
            'language'       => 'fr',
            'name'           => 'Petit Larousse / N. propres',
            'search'         => array(
                'properties' => array(
                    'url'     => array(
                        5 => 'http://ia600501.us.archive.org/BookReader/BookReaderImages.php?zip=/19/items/PetitLarousse19065/larousse_petit_1906_e_jp2.zip&file=larousse_petit_1906_e_jp2/larousse_petit_1906_e_0%03u.jp2&scale=3&rotate=0',
                        6 => 'http://ia700503.us.archive.org/BookReader/BookReaderImages.php?zip=/10/items/PetitLarousse19066/larousse_petit_1906_f_jp2.zip&file=larousse_petit_1906_f_jp2/larousse_petit_1906_f_0%03u.jp2&scale=3&rotate=0',
                    ),
                )
            ),
            'title'          => 'N.p. Larousse',
            'type'           => 'index',
            'volume'         => 'readonly',
        ),

        'renart-meon-1' => array(
            'availability-test' => 'acorde',
            'created'        => '2012-01-07',
            'description'    => 'Glossaire du Roman de Renart, volume 1, M. D. M. Méon, 1826',
            'description-en' => 'Glossary of the Roman de Renart, volume 1, M. D. M. Méon, 1826',
            'image'          => 'glossaire-roman-de-renart-meon-vol1.jpg',
            'language'       => 'fr',
            'name'           => 'Roman de Renart / Méon v.1',
            'parser'         => array(
                'class'      => 'Model_Parser_GaffiotLike',
                'properties' => array(
                    'imageNumberTpl' => '0000%s',
                    'lineTpl'        => '~^(.+?);(\d+)~',
                )
            ),
            'search'         => array(
                'properties' => array(
                    'needTcaf'   => true,
                    'needTobler' => true,
                )
            ),
            'title'          => 'Méon 1',
            'type'           => 'internal',
            'url'            => 'glossaire-roman-de-renart-meon-vol1',
        ),

        'renart-meon-2' => array(
            'availability-test' => 'adolé',
            'created'        => '2012-01-07',
            'description'    => 'Glossaire du Roman de Renart, volume 2, M. D. M. Méon, 1826',
            'description-en' => 'Glossary of the Roman de Renart, volume 2, M. D. M. Méon, 1826',
            'image'          => 'glossaire-roman-de-renart-meon-vol2.jpg',
            'language'       => 'fr',
            'name'           => 'Roman de Renart / Méon v.2',
            'parser'         => array(
                'class'      => 'Model_Parser_GaffiotLike',
                'properties' => array(
                    'imageNumberTpl' => '0000%s',
                    'lineTpl'        => '~^(.+?);(\d+)~',
                )
            ),
            'search'         => array(
                'properties' => array(
                    'needTcaf'   => true,
                    'needTobler' => true,
                )
            ),
            'title'          => 'Méon 2',
            'type'           => 'internal',
            'url'            => 'glossaire-roman-de-renart-meon-vol2',
        ),

        'renart-meon-3' => array(
            'availability-test' => 'aignel',
            'created'        => '2012-01-07',
            'description'    => 'Glossaire du Roman de Renart, volume 3, M. D. M. Méon, 1826',
            'description-en' => 'Glossary of the Roman de Renart, volume 3, M. D. M. Méon, 1826',
            'image'          => 'glossaire-roman-de-renart-meon-vol3.jpg',
            'language'       => 'fr',
            'name'           => 'Roman de Renart / Méon v.3',
            'parser'         => array(
                'class'      => 'Model_Parser_GaffiotLike',
                'properties' => array(
                    'imageNumberTpl' => '0000%s',
                    'lineTpl'        => '~^(.+?);(\d+)~',
                )
            ),
            'search'         => array(
                'properties' => array(
                    'needTcaf'   => true,
                    'needTobler' => true,
                )
            ),
            'title'          => 'Méon 3',
            'type'           => 'internal',
            'url'            => 'glossaire-roman-de-renart-meon-vol3',
        ),

        'renart-meon-4' => array(
            'availability-test' => 'acroire',
            'created'        => '2012-01-07',
            'description'    => 'Glossaire du Roman de Renart, volume 4, M. D. M. Méon, 1826',
            'description-en' => 'Glossary of the Roman de Renart, volume 4, M. D. M. Méon, 1826',
            'image'          => 'glossaire-roman-de-renart-meon-vol4.jpg',
            'language'       => 'fr',
            'name'           => 'Roman de Renart / Méon v.4',
            'parser'         => array(
                'class'      => 'Model_Parser_GaffiotLike',
                'properties' => array(
                    'imageNumberTpl' => '0000%s',
                    'lineTpl'        => '~^(.+?);(\d+)~',
                )
            ),
            'search'         => array(
                'properties' => array(
                    'needTcaf'   => true,
                    'needTobler' => true,
                )
            ),
            'title'          => 'Méon 4',
            'type'           => 'internal',
            'url'            => 'glossaire-roman-de-renart-meon-vol4',
        ),

        'roland' => array(
            'availability-test' => 'acuminier',
            'created'        => '2010-08-01',
            'description'    => 'Glossaire de la Chanson de Roland, Joseph Bédier, 1927',
            'description-en' => 'Glossary of the Song of Roland, Joseph Bédier, 1927',
            'image'          => 'glossaire-chanson-de-roland.jpg',
            'language'       => 'fr',
            'name'           => 'Chanson de Roland',
            'parser'         => array(
                'class'      => 'Model_Parser_GaffiotLike',
                'properties' => array(
                    'imageNumberTpl' => '0000%s',
                    'lineTpl'        => '~^(.+?)__BR____BR__<@_tx(\d+).tif_>EGlos_RolB__BR____BR__~',
                )
            ),
            'search'         => array(
                'properties' => array(
                    'needTcaf'   => true,
                    'needTobler' => true,
                )
            ),
            'title'          => 'Roland',
            'type'           => 'internal',
            'url'            => 'glossaire-chanson-de-roland',
        ),

        'roman-de-renart-fhs-glossary' => array(
            'availability-test' => 'aramir',
            'created'        => '2012-01-07',
            'description'    => 'Glossaire du Roman de Renart, N. Fukumoto, N. Harano et S. Suzuki, 1985',
            'description-en' => 'Glossary of the Roman de Renart, N. Fukumoto, N. Harano et S. Suzuki, 1985',
            'image'          => 'glossaire-roman-de-renart-fhs.jpg',
            'language'       => 'fr',
            'name'           => 'Roman de Renart / FHS',
            'parser'         => array(
                'class'      => 'Model_Parser_GaffiotLike',
                'properties' => array(
                    'imageNumberTpl' => '0000%s',
                    'lineTpl'        => '~^(.+?);(\d+)~',
                )
            ),
            'search'         => array(
                'properties' => array(
                    'imagePath'  => 'old-french-glossaries/renart-fhs/mImg/%s.gif',
                    'needTcaf'   => true,
                    'needTobler' => true,
                )
            ),
            'title'          => 'Renart',
            'type'           => 'internal',
            'url'            => 'glossaire-roman-de-renart-fhs',
        ),

        'roman-lexicon' => array(
            'availability-test' => 'abat',
            'created'        => '2010-08-01',
            'description'    => "Lexique Roman ou dictionnaire de la langue des troubadours comparée avec les autres langues de l'Europe latine, François J. M. Raynouard, 1844",
            'description-en' => 'Romance languages lexicon or dictionary of the language of the troubadours compared with other languages ​​of Latin Europe, François J. M. Raynouard, 1844',
            'image'          => 'lexique-roman.jpg',
            'introduction'   => 'roman.phtml',
            'language'       => 'fr',
            'name'           => 'Lexique Roman',
            'search'         => 'roman-lexicon/lexromv/mImg/%s.gif',
            'title'          => 'L. Roman',
            'type'           => 'internal',
            'url'            => 'lexique-roman',
        ),

        'rose' => array(
            'availability-test' => 'aaise',
            'created'        => '2010-08-01',
            'description'    => 'Glossaire du Roman de la Rose, Ernest Langlois, 1914-1924',
            'description-en' => 'Glossary of the Roman de la Rose, Ernest Langlois, 1914-1924',
            'image'          => 'glossaire-roman-de-la-rose.jpg',
            'language'       => 'fr',
            'name'           => 'Roman de la Rose',
            'parser'         => array(
                'class'      => 'Model_Parser_GaffiotLike',
                'properties' => array(
                    'imageNumberTpl' => '0000%s',
                    'lineTpl'        => '~^(.+?)__BR____BR__<@_hm(\d+).tif_>EGlos_RoseLLangl__BR____BR__~',
                )
            ),
            'search'         => array(
                'properties' => array(
                    'needTcaf'   => true,
                    'needTobler' => true,
                )
            ),
            'title'          => 'Rose',
            'type'           => 'internal',
            'url'            => 'glossaire-roman-de-la-rose',
        ),

        'tcaf' => array(
            'availability-test' => 'avoir',
            'created'        => '2010-08-01',
            'description'    => "Tableaux de conjugaison de l'ancien français (TCAF), Machio Okada et Hitoshi Ogurisu, 2007-2012",
            'description-en' => 'Old French conjugation tables (TCAF), Machio Okada and Hitoshi Ogurisu, 2007-2011',
            'image'          => 'tableaux-de-conjugaison.jpg',
            'language'       => 'fr',
            'name'           => 'Conjugaison',
            'search'         => array(
                'class' => 'Model_Search_Tcaf',
            ),
            'title'          => 'TCAF',
            'type'           => 'internal',
            'url'            => 'tableaux-de-conjugaison',
        ),

        'tlfi' => array(
            'availability-test' => 'avoir',
            'created'           => '2010-08-01',
            'description'       => 'Le Trésor de la Langue Française informatisé (TLFi), Centre National de Ressources Textuelles et Lexicales',
            'description-en'    => 'The Computerized Treasury of the French Language (TLFi), the National Center for Textual and Lexical Resources',
            'introduction'      => 'http://www.cnrtl.fr/definition/',
            'language'          => 'fr',
            'name'              => 'Trésor de la Langue Fr.',
            'search'            => 'http://www.cnrtl.fr/definition/',
            'title'             => 'TLFi',
            'type'              => 'external',
            'updated'           => '2014-05-02',
        ),

        'tristan' => array(
            'availability-test' => 'acorder',
            'created'        => '2010-08-01',
            'description'    => 'Glossaire du Roman de Tristan par Béroul, Ernest Muret, 1903',
            'description-en' => 'Glossary of the Roman de Tristan by Béroul, Ernest Muret, 1903',
            'image'          => 'glossaire-roman-de-tristan.jpg',
            'language'       => 'fr',
            'name'           => 'Roman de Tristan',
            'parser'         => array(
                'class'      => 'Model_Parser_GaffiotLike',
                'properties' => array(
                    'endWord' => '_Glossaire',
                    'lineTpl' => '~^(.+?)__BR____BR__<@_tx(\d+).tif_>EGlos_TristBerM1__BR____BR__~',
                )
            ),
            'search'         => array(
                'properties' => array(
                    'needTcaf'   => true,
                    'needTobler' => true,
                )
            ),
            'title'          => 'Tristan',
            'type'           => 'internal',
            'url'            => 'glossaire-roman-de-tristan',
        ),

        'vandaele' => array(
            'availability-test' => 'aan',
            'created'        => '2008-06-27',
            'description'    => "Petit dictionnaire de l'ancien français, Hilaire Van Daele, 1901",
            'description-en' => "Little dictionary of Old French, Hilaire Van Daele, 1901",
            'image'          => 'vandaele.jpg',
            'language'       => 'fr',
            'name'           => 'Van Daele',
            'search'         => array(
                'properties' => array(
                    'needGhostwords' => true,
                    'needTcaf'       => true,
                    'needTobler'     => true,
                )
            ),
            'type'           => 'internal',
        ),

        'whitaker' => array(
            'availability-test' => 'A',
            'created'        => '2010-09-16',
            'description'    => 'Dictionnaire latin-anglais, William Whitaker, 1997-2007',
            'description-en' => 'Latin-English dictionary, William Whitaker, 1997-2007',
            'introduction'   => 'http://lysy2.archives.nd.edu/cgi-bin/words.exe',
            'language'       => 'la',
            'name'           => 'Whitaker',
            'search'         => array(
                'properties' => array(
                    'convert' => 'utf8toASCII',
                    'url'     => 'http://lysy2.archives.nd.edu/cgi-bin/words.exe?',
                ),
            ),
            'type'           => 'external',
        ),

        'webster' => array(
            'availability-test' => 'avid',
            'created'           => '2012-06-03',
            'description'       => "Webster's Revised Unabridged Dictionary, 1913, 1828, The ARTFL project",
            'introduction'      => 'http://machaut.uchicago.edu/websters',
            'language'          => 'en',
            'name'              => 'Webster',
            'search'            => 'http://machaut.uchicago.edu/?action=search&resource=Webster%27s&quicksearch=on&word=',
            'type'              => 'external',
        ),

        'wiktionary' => array(
            'availability-test' => 'avid',
            'created'           => '2012-06-03',
            'description'       => 'Wiktionary, the free dictionary, wiktionary.org',
            'introduction'      => 'http://en.wiktionary.org',
            'language'          => 'en',
            'name'              => 'Wiktionary',
            'search'            => 'http://en.wiktionary.org/wiki/',
            'type'              => 'external',
        ),

        'wiktionnaire' => array(
            'availability-test' => 'avoir',
            'created'           => '2012-03-11',
            'description'       => 'Wiktionnaire, le dictionnaire libre, wiktionary.org',
            'description-en'    => 'French Wiktionary, the free dictionary, wiktionary.org',
            'introduction'      => 'http://fr.wiktionary.org',
            'language'          => 'fr',
            'name'              => 'Wiktionnaire',
            'search'            => 'http://fr.wiktionary.org/wiki/',
            'type'              => 'external',
        ),
    ),

    // sets the domain subpath
    // this MUST BE kept in sync with .htaccess dictionary rewrite rule
    // dicfro domain subpath autodetection, default is none
    'domain-subpath' => (isset($_SERVER['REQUEST_URI']) and strpos($_SERVER['REQUEST_URI'], '/dicfro') === 0)? 'dicfro' : '',
    // $domainSubpath = '';    // installation with no domain subpath, eg local installation
    // $domainSubpath = 'xyz'; // installation with "xyz" domain subpath

    // dictionary groups, displayed as they are ordered below in the home page and select box
    'groups' => array(
        array(
            'dictionaries' => array(
                'chambers-encyclopedia',
                'century',
                'century-supplement',
                'etymonline',
                'webster',
                'wiktionary',
            ),
            'language' => 'en',
        ),
        array(
            'dictionaries' => array(
                'conjugueur',
                'dvlf',
                'encyclopedie-larousse',
                'grand-larousse',
                'littre',
                'dmf',
                'orthonet',
                'petit-larousse',
                'petit-larousse-np',
                'tlfi',
                'wiktionnaire',
            ),
            'language' => 'fr',
        ),
        array(
            'dictionaries' => array(
                'roland',
                'chretien-de-troyes-glossary',
                'tcaf',
                'cotgrave',
                'couronnement-de-louis-glossary',
                'gdf',
                'gdfc',
                'gdflex',
                'lacurne-dictionary',
                'roman-lexicon',
                'rose',
                'roman-de-renart-fhs-glossary',
                'renart-meon-1',
                'renart-meon-2',
                'renart-meon-3',
                'renart-meon-4',
                'tristan',
                'vandaele',
            ),
            'language' => 'fro',
        ),
        array(
            'dictionaries' => array(
                'ducange',
                'gaffiot-dictionary',
                'jeanneau',
                'whitaker',
            ),
            'language' => 'la',
        ),
    ),

    // directory containing translations
    'translations-dir' => realpath("$applicationDir/translations"),

    // HTML content directory
    'views-dir' => realpath("$applicationDir/View/scripts"),
);