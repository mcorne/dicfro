<?php

/**
 * Dicfro
 *
 * PHP 5
 *
 * @category  DicFro
 * @package   Config
 * @author    Michel Corne <mcorne@yahoo.com>
 * @copyright 2008-2013 Michel Corne
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

/**
 * Application configuration file
 */

// sets domain subpath
// dicfro domain subpath autodetection, default is none
$domainSubpath = (isset($_SERVER['REQUEST_URI']) and strpos($_SERVER['REQUEST_URI'], '/dicfro') === 0)? 'dicfro' : '';
// $domainSubpath = '';    // installation with no domain subpath, eg local installation
// $domainSubpath = 'xyz'; // installation with "xyz" domain subpath

$applicationDir = dirname(__FILE__); // global variable used by scripts

return array(
    // data directory containing indexes and databases
    'data-dir' => realpath("$applicationDir/data"),

    // default dictionary for each language
    'dictionary-defaults' => array(
        'en' => 'century',
        'fr' => 'gdf',
    ),

    // dictionaries details
    'dictionaries' => array(
        'anglo-norman' => array(
            'created'      => '2013-03-18',
            'description'  => 'The Anglo-Norman dictionary',
            'introduction' => 'http://www.anglo-norman.net',
            'language'     => 'fr',
            'name'         => 'Anglo-Norman',
            'search'       => 'http://www.anglo-norman.net/cgi-bin/form-s1?term1=',
            'title'        => 'AND',
            'type'         => 'external',
        ),

        'century' => array(
            'created'      => '2013-13-14',
            'description'  => 'The Century dictionary, an encyclopedic lexicon of the English language, William D. Whitney, 1895',
            'image'        => 'century-dictionary.jpg',
            'introduction' => 'century-dictionary.phtml',
            'language'     => 'en',
            'name'         => 'Century Dictionary',
            'search'       => array(
                'properties' => array(
                    'url'     => array(
                        1 => 'http://ia600305.us.archive.org/BookReader/BookReaderImages.php?zip=/29/items/centurydict01whit/centurydict01whit_jp2.zip&file=centurydict01whit_jp2/centurydict01whit_0%03u.jp2&scale=3&rotate=0',
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
            'created'      => '2013-13-18',
            'description'  => 'The Century dictionary supplement, Benjamin E. Smith, 1909',
            'image'        => 'century-supplement.jpg',
            'language'     => 'en',
            'name'         => 'Century Supplement',
            'search'       => array(
                'properties' => array(
                    'url'     => array(
                        11 => 'http://ia700404.us.archive.org/BookReader/BookReaderImages.php?zip=/4/items/cu31924091890685/cu31924091890685_jp2.zip&file=cu31924091890685_jp2/cu31924091890685_0%03u.jp2&scale=3&rotate=0',
                        12 => 'http://ia700305.us.archive.org/BookReader/BookReaderImages.php?zip=/7/items/centurydictionar12whituoft/centurydictionar12whituoft_jp2.zip&file=centurydictionar12whituoft_jp2/centurydictionar12whituoft_0%03u.jp2&scale=3&rotate=0',
                    ),
                )
            ),
            'title'        => 'Century S.',
            'type'         => 'index',
            'volume'       => 'readonly',
        ),

        'chretien' => array(
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

        'cnrtl' => array(
            'created'        => '2010-08-01',
            'description'    => 'Dictionnaire du Centre National de Ressources Textuelles et Lexicales',
            'description-en' => 'Dictionary of the national center for textual and lexical resources',
            'introduction'   => 'http://www.cnrtl.fr/definition/',
            'language'       => 'fr',
            'name'           => 'CNRTL',
            'search'         => 'http://www.cnrtl.fr/definition/',
            'type'           => 'external',
        ),

        'cotgrave' => array(
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

        'couronnement' => array(
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
            'created'        => '2008-04-14',
            'description'    => "Dictionnaire du Moyen Français, Atilf",
            'description-en' => "Middle French dictionary, Atilf",
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
            'created'        => '2013-03-19',
            'description'    => 'Glossarium mediæ et infimæ latinitatis, Du Cange, 1678',
            'description-en' => 'Glossary of medieval and late Latin, Du Cange, 1678',
            'introduction'   => 'http://ducange.enc.sorbonne.fr/',
            'language'       => 'la',
            'name'           => 'Du Cange',
            'search'         => 'http://ducange.enc.sorbonne.fr/',
            'title'          => 'Du Cange',
            'type'           => 'external',
        ),

        'dvlf' => array(
            'created'        => '2012-06-03',
            'description'    => 'Dictionnaire vivant de la langue française, The ARTFL Project',
            'description-en' => 'Live dictionary of the French language, The ARTFL Project',
            'introduction'   => 'http://dvlf.uchicago.edu/',
            'language'       => 'fr',
            'name'           => 'DVLF',
            'search'         => array(
                'properties' => array(
                    'emptyWord' => 'http://dvlf.uchicago.edu/',
                    'url'       => 'http://dvlf.uchicago.edu/mot/',
                )
            ),
            'type'           => 'external',
        ),

        'encyclopedie-larousse' => array(
            'created'        => '2013-04-03',
            'description'    => 'La Grande encyclopédie Larousse, 1971-1976',
            'description-en' => 'The Larousse great encyclopedia, 1971-1976',
            'image'          => 'encyclopedie-larousse.jpg',
            'language'       => 'fr',
            'name'           => 'Encyclopédie Larousse (Aa-Au)',
            'parser'         => array(
                'class'      => 'Model_Parser_Index',
                'properties' => array(
                    'singleWord' => true,
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
            'volume'         => 'readonly',
        ),

        'gaffiot' => array(
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
            'created'        => '2008-04-14',
            'description'    => "Dictionnaire de l'ancienne langue française et de tous ses dialectes du IXème au XVème siècle, Frédéric Godefroy, 1880-1895",
            'description-en' => 'Dictionary of the Old French language and all its dialects from the ninth to fifteenth century, Frédéric Godefroy, 1880-1895',
            'image'          => 'dictionnaire-godefroy.jpg',
            'language'       => 'fr',
            'name'           => 'Godefroy - Dictionnaire',
            'parser'         => 'Model_Parser_GdfLike',
            'search'         => array(
                'properties' => array(
                    'errataFiles'      => 'dictionary/gdf/mPimg-3/%s-%s[a-z]*/*.gif',
                    'imagePath'        => 'dictionary/gdf/mImg/%s.gif',
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
            'created'        => '2008-04-14',
            'description'    => "Complément du dictionnaire de l'ancienne langue française et de tous ses dialectes du IXème au XVème siècle, Frédéric Godefroy, 1895-1902",
            'description-en' => 'Dictionary supplement of the Old French language and all its dialects from the ninth to fifteenth century, Frédéric Godefroy, 1880-1895',
            'image'          => 'complement-godefroy.jpg',
            'language'       => 'fr',
            'name'           => 'Godefroy - Complément',
            'parser'         => 'Model_Parser_GdfLike',
            'search'         => array(
                'properties' => array(
                    'digit'            => 1,
                    'errataFiles'      => 'dictionary/gdfc/mPimg-3/%s-%s[a-z]*/*.gif',
                    'imagePath'        => 'dictionary/gdfc/mImg/%s.gif',
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
            'created'        => '2008-04-14',
            'description'    => "Lexique de l'ancien français, Frédéric Godefroy, 1901",
            'description-en' => 'Old French lexicon, Frédéric Godefroy, 1880-1895',
            'image'          => 'lexique-godefroy.jpg',
            'language'       => 'fr',
            'name'           => 'Godefroy - Lexique',
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

        'jeanneau' => array(
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

        'lacurne' => array(
            'created'        => '2013-03-29',
            'description'    => "Dictionnaire historique de l'ancien langage françois ou glossaire de la langue françoise, J.B. de la Curne de Sainte-Palaye, 1875-1882",
            'description-en' => 'Historical dictionary of the Old French language, J.B. de la Curne de Sainte-Palaye, 1875-1882',
            'image'          => 'dictionnaire-lacurne.jpg',
            'language'       => 'fr',
            'name'           => 'Lacurne - Dictionnaire',
            'search'         => array(
                'properties' => array(
                    'needTcaf'   => true,
                    'needTobler' => true,
                )
            ),
            'title'          => 'D. Lacurne',
            'type'           => 'internal',
            'url'            => 'dictionnaire-lacurne',
            'volume'         => 'input',
        ),

        'les-verbes' => array(
            'created'        => '2013-04-21',
            'description'    => 'Les verbes, les-verbes.com',
            'description-en' => 'Verbs, les-verbes.com',
            'introduction'   => 'http://www.les-verbes.com',
            'language'       => 'fr',
            'name'           => 'Les verbes',
            'search'         => array(
                'properties' => array(
                    'emptyWord' => 'http://www.les-verbes.com',
                    'url'       => 'http://www.les-verbes.com/conjuguer.php?verbe=',
                )
            ),
            'type'           => 'external',
        ),

        'lexromv' => array(
            'created'        => '2010-08-01',
            'description'    => "Lexique Roman ou dictionnaire de la langue des troubadours comparée avec les autres langues de l'Europe latine, François J. M. Raynouard, 1844",
            'description-en' => 'Romance languages lexicon or dictionary of the language of the troubadours compared with other languages ​​of Latin Europe, François J. M. Raynouard, 1844',
            'image'          => 'lexique-roman.jpg',
            'introduction'   => 'roman.phtml',
            'language'       => 'fr',
            'name'           => 'Lexique Roman',
            'search'         => array(
                'class' => 'Model_Search_Lexromv',
            ),
            'title'          => 'L. Roman',
            'type'           => 'internal',
            'url'            => 'lexique-roman',
        ),

        'littre' => array(
            'created'        => '2010-08-01',
            'description'    => 'Dictionnaire de la langue française, Émile Littré, 1872-1877',
            'description-en' => 'Dictionary of the French language, Émile Littré, 1872-1877',
            'introduction'   => 'http://littre.reverso.net/dictionnaire-francais',
            'language'       => 'fr',
            'name'           => 'Littré',
            'search'         => 'http://littre.reverso.net/dictionnaire-francais/definition/',
            'type'           => 'external',
        ),

        'petit-larousse' => array(
            'created'        => '2013-04-10',
            'description'    => "Petit Larousse illustré (noms communs), nouveau dictionnaire encyclopédique (5e édition), Claude Augé, 1906",
            'description-en' => "Petit Larousse illustrated dictionary (common names), new encyclopedic dictionary (5th Edition), Claude Augé, 1906",
            'image'          => 'petit-larousse.jpg',
            'language'       => 'fr',
            'name'           => 'Petit Larousse (N.c.)',
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
            'created'        => '2013-04-13',
            'description'    => "Petit Larousse illustré (noms propres), nouveau dictionnaire encyclopédique (5e édition), Claude Augé, 1906",
            'description-en' => "Petit Larousse illustrated dictionary (proper names), new encyclopedic dictionary (5th Edition), Claude Augé, 1906",
            'image'          => 'petit-larousse-np.jpg',
            'language'       => 'fr',
            'name'           => 'Petit Larousse (N.p.)',
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

        'renart-fhs' => array(
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
                    'needTcaf'   => true,
                    'needTobler' => true,
                )
            ),
            'title'          => 'Renart',
            'type'           => 'internal',
            'url'            => 'glossaire-roman-de-renart-fhs',
        ),

        'renart-meon-1' => array(
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

        'rose' => array(
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
            'created'        => '2010-08-01',
            'description'    => "Tableaux de conjugaison de l'ancien français, Machio Okada et Hitoshi Ogurisu, 2007-2012",
            'description-en' => 'Old French conjugation tables, Machio Okada and Hitoshi Ogurisu, 2007-2011',
            'image'          => 'tableaux-de-conjugaison.jpg',
            'language'       => 'fr',
            'name'           => 'TCAF',
            'search'         => array(
                'class' => 'Model_Search_Tcaf',
            ),
            'title'          => 'Conjugaisons',
            'type'           => 'internal',
            'url'            => 'tableaux-de-conjugaison',
        ),

        'tristan' => array(
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
            'created'      => '2012-06-03',
            'description'  => "Webster's Revised Unabridged Dictionary, 1913, 1828, The ARTFL project",
            'introduction' => 'http://machaut.uchicago.edu/websters',
            'language'     => 'en',
            'name'         => 'Webster',
            'search'       => 'http://machaut.uchicago.edu/?action=search&resource=Webster%27s&quicksearch=on&word=',
            'type'         => 'external',
        ),

        'wiktionary' => array(
            'created'      => '2012-06-03',
            'description'  => 'Wiktionary, the free dictionary, wiktionary.org',
            'introduction' => 'http://en.wiktionary.org',
            'language'     => 'en',
            'name'         => 'Wiktionary',
            'search'       => 'http://en.wiktionary.org/wiki/',
            'type'         => 'external',
        ),

        'wiktionnaire' => array(
            'created'        => '2012-03-11',
            'description'    => 'Wiktionnaire, le dictionnaire libre, wiktionary.org',
            'description-en' => 'French Wiktionary, the free dictionary, wiktionary.org',
            'introduction'   => 'http://fr.wiktionary.org',
            'language'       => 'fr',
            'name'           => 'Wiktionnaire',
            'search'         => 'http://fr.wiktionary.org/wiki/',
            'type'           => 'external',
        ),
    ),

    // domain subpath set in .htaccess, ex. "dicfro" from "www.micmap.org/dicfro"
    'domain-subpath' => $domainSubpath,

    // dictionary groups, displayed as they are ordered below in the home page and select box
    'groups' => array(
        array(
            'dictionaries' => array(
                'century',
                'century-supplement',
                'webster',
                'wiktionary',
            ),
            'language' => 'en',
        ),
        array(
            'dictionaries' => array(
                'cnrtl',
                'dvlf',
                'encyclopedie-larousse',
                'les-verbes',
                'littre',
                'dmf',
                'petit-larousse',
                'petit-larousse-np',
                'wiktionnaire',
            ),
            'language' => 'fr',
        ),
        array(
            'dictionaries' => array(
                'anglo-norman',
                'roland',
                'chretien',
                'cotgrave',
                'couronnement',
                'gdf',
                'gdfc',
                'gdflex',
                'lacurne',
                'lexromv',
                'rose',
                'renart-fhs',
                'renart-meon-1',
                'renart-meon-2',
                'renart-meon-3',
                'renart-meon-4',
                'tristan',
                'tcaf',
                'vandaele',
            ),
            'language' => 'fro',
        ),
        array(
            'dictionaries' => array(
                'ducange',
                'gaffiot',
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