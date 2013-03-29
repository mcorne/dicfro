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

    // dictionaries details
    'dictionaries' => array(
        'anglo-norman' => array(
            'description'  => 'The Anglo-Norman Dictionary',
            'introduction' => 'http://www.anglo-norman.net',
            'language'     => 'fr',
            'name'         => 'Anglo-Norman',
            'search'       => 'http://www.anglo-norman.net/cgi-bin/form-s1?term1=',
            'title'        => 'AND',
        ),

        'chretien' => array(
            'description' => "Dictionnaire de l'oeuvre de Chrétien de Troyes, Wörterbuch zu Kristian von Troyes' sämtlichen Werken, Wendelin Foerster, 1914",
            'file'        => 'dictionnaire-chretien-de-troyes.jpg',
            'internal'    => true,
            'language'    => 'fr',
            'name'        => 'Chrétien de Troyes',
            'search'      => array(
                'properties' => array(
                    'needTcaf'   => true,
                    'needTobler' => true,
                )
            ),
            'title'       => 'Chrétien',
            'url'         => 'glossaire-chretien-de-troyes',
        ),

        'cnrtl' => array(
            'description'  => 'Dictionnaire du Centre National de Ressources Textuelles et Lexicales',
            'introduction' => 'http://www.cnrtl.fr/definition/',
            'language'     => 'fr',
            'name'         => 'CNRTL',
            'search'       => 'http://www.cnrtl.fr/definition/',
        ),

        'cotgrave' => array(
            'description'  => 'A Dictionarie of the French and English Tongues, Randle Cotgrave, 1611',
            'introduction' => 'http://www.pbm.com/~lindahl/cotgrave/',
            'language'     => 'fr',
            'name'         => 'Cotgrave',
            'search'       => 'http://www.pbm.com/~lindahl/cotgrave/search/search_backend.cgi?query=',
            // to: en
        ),

        'couronnement' => array(
            'description' => 'Glossaire du Couronnement de Louis, Ernest Langlois, 1888',
            'file'        => 'glossaire-couronnement-de-louis.jpg',
            'internal'    => true,
            'language'    => 'fr',
            'name'        => 'Couronnement de Louis',
            'search'      => array(
                'properties' => array(
                    'needTcaf'   => true,
                    'needTobler' => true,
                )
            ),
            'title'       => 'Couronnement',
            'url'         => 'glossaire-couronnement-de-louis',
        ),

        'dmf' => array(
            'description'  => "Dictionnaire du Moyen Français par l'Atilf",
            'introduction' => 'http://www.atilf.fr/dmf/',
            'language'     => 'fr',
            'name'         => 'Moyen français',
            'search'       => 'http://www.atilf.fr/dmf/definition/',
            'title'        => 'DMF',
        ),

        'ducange' => array(
            'description'  => 'Du Cange, et al., Glossarium mediæ et infimæ latinitatis. Niort : L. Favre, 1883-1887',
            'introduction' => 'http://ducange.enc.sorbonne.fr/',
            'language'     => 'la',
            'name'         => 'Du Cange',
            'search'       => 'http://ducange.enc.sorbonne.fr/',
            'title'         => 'Du Cange',
            // to: fr, grc, la
        ),

        'dvlf' => array(
            'description'  => "Dictionnaire vivant de la langue française du projet ARTFL",
            'introduction' => 'http://dvlf.uchicago.edu/',
            'language'     => 'fr',
            'name'         => 'DVLF',
            'search'       => 'http://dvlf.uchicago.edu/mot/',
        ),

        'gaffiot' => array(
            'description' => 'Dictionnaire Latin-Français, Félix Gaffiot, 1934',
            'file'        => 'gaffiot.jpg',
            'internal'    => true,
            'language'    => 'la',
            'name'        => 'Gaffiot',
            'search'      => array(
                'properties' => array(
                    'needWhitaker' => true,
                )
            ),
            // to: fr
        ),

        'gdf' => array(
            'description' => "Dictionnaire de l'ancienne langue française et de tous ses dialectes du IXème au XVème siècle, Frédéric Godefroy, 1880-1895",
            'file'        => 'dictionnaire-godefroy.jpg',
            'internal'    => true,
            'is-volumes'  => true,
            'language'    => 'fr',
            'name'        => 'Godefroy - Dictionnaire',
            'search'      => array(
                'properties' => array(
                    'errataFiles'      => 'dictionary/gdf/mPimg-3/%s-%s[a-z]*/*.gif',
                    'imagePath'        => 'dictionary/gdf/mImg/%s.gif',
                    'needErrataImages' => true,
                    'needGhostwords'   => true,
                    'needTcaf'         => true,
                    'needTobler'       => true,
                )
            ),
            'title'       => 'D. Godefroy',
            'url'         => 'dictionnaire-godefroy',
        ),

        'gdfc' => array(
            'description' => "Complément du dictionnaire de l'ancienne langue française et de tous ses dialectes du IXème au XVème siècle, Frédéric Godefroy, 1895-1902",
            'file'        => 'complement-godefroy.jpg',
            'internal'    => true,
            'is-volumes'  => true,
            'language'    => 'fr',
            'name'        => 'Godefroy - Complément',
            'search'      => array(
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
            'title'       => 'C. Godefroy',
            'url'         => 'complement-godefroy',
        ),

        'gdflex' => array(
            'description' => "Lexique de l'ancien français, Frédéric Godefroy, 1901",
            'file'        => 'lexique-godefroy.jpg',
            'internal'    => true,
            'language'    => 'fr',
            'name'        => 'Godefroy - Lexique',
            'query'       => array(
                'properties' => array(
                    'extraColumns' => ', errata',
                ),
            ),
            'search'      => array(
                'properties' => array(
                    'needErrataText' => true,
                    'needGhostwords' => true,
                    'needTcaf'       => true,
                    'needTobler'     => true,
                )
            ),
            'title'       => 'L. Godefroy',
            'url'         => 'lexique-godefroy',
        ),

        'jeanneau' => array(
            'description'  => 'Dictionnaire français-latin de Gérard Jeanneau',
            'introduction' => 'http://www.prima-elementa.fr/Dico.htm',
            'language'     => 'la',
            'name'         => 'Jeanneau',
            'search'       => array(
                'class' => 'Model_Search_Jeanneau',
            ),
            // to: fr
        ),

        'lacurne' => array(
            'description' => "Dictionnaire historique de l'ancien langage françois ou Glossaire de la langue françoise, J.B. de la Curne de Sainte-Palaye, 1875-1882",
            'file'        => 'dictionnaire-lacurne.jpg',
            'internal'    => true,
            'is-volumes'  => true,
            'language'    => 'fr',
            'name'        => 'Lacurne - Dictionnaire',
            'search'      => array(
                'properties' => array(
                    'needTcaf'   => true,
                    'needTobler' => true,
                )
            ),
            'title'       => 'D. Lacurne',
            'url'         => 'dictionnaire-lacurne',
        ),

        'leconjugueur' => array(
            'description'  => 'La conjugaison française par le Conjugueur',
            'introduction' => 'http://www.leconjugueur.com/',
            'language'     => 'fr',
            'name'         => 'Le conjugueur',
            'search'       => 'http://www.leconjugueur.com/php5/index.php?v=',
        ),

        'lexromv' => array(
            'description'  => "Lexique Roman ou dictionnaire de la langue des troubadours comparée avec les autres langues de l'Europe latine, François J. M. Raynouard, 1844",
            'file'         => 'lexique-roman.jpg',
            'internal'     => true,
            'introduction' => 'roman.phtml',
            'language'     => 'fr',
            'name'         => 'Lexique Roman',
            'title'        => 'L. Roman',
            'url'          => 'lexique-roman',
        ),

        'littre' => array(
            'description'  => "Dictionnaire de la langue française d'Émile Littré, 1872-1877",
            'introduction' => 'http://francois.gannaz.free.fr/Littre/',
            'language'     => 'fr',
            'name'         => 'Littré',
            'search'       => 'http://francois.gannaz.free.fr/Littre/xmlittre.php?requete=',
        ),

        'renart-fhs' => array(
            'description' => 'Glossaire du Roman de Renart, N. Fukumoto, N. Harano et S. Suzuki, 1985',
            'file'        => 'glossaire-roman-de-renart-fhs.jpg',
            'internal'    => true,
            'language'    => 'fr',
            'name'        => 'Roman de Renart / FHS',
            'search'      => array(
                'properties' => array(
                    'needTcaf'   => true,
                    'needTobler' => true,
                )
            ),
            'title'       => 'Renart',
            'url'         => 'glossaire-roman-de-renart-fhs',
        ),

        'renart-meon-1' => array(
            'description' => 'Glossaire du Roman de Renart, volume 1, M. D. M. Méon, 1826',
            'file'        => 'glossaire-roman-de-renart-meon-vol1.jpg',
            'internal'    => true,
            'language'    => 'fr',
            'name'        => 'Roman de Renart / Méon v.1',
            'search'      => array(
                'properties' => array(
                    'needTcaf'   => true,
                    'needTobler' => true,
                )
            ),
            'title'       => 'Méon 1',
            'url'         => 'glossaire-roman-de-renart-meon-vol1',
        ),

        'renart-meon-2' => array(
            'description' => 'Glossaire du Roman de Renart, volume 2, M. D. M. Méon, 1826',
            'file'        => 'glossaire-roman-de-renart-meon-vol2.jpg',
            'internal'    => true,
            'language'    => 'fr',
            'name'        => 'Roman de Renart / Méon v.2',
            'search'      => array(
                'properties' => array(
                    'needTcaf'   => true,
                    'needTobler' => true,
                )
            ),
            'title'       => 'Méon 2',
            'url'         => 'glossaire-roman-de-renart-meon-vol2',
        ),

        'renart-meon-3' => array(
            'description' => 'Glossaire du Roman de Renart, volume 3, M. D. M. Méon, 1826',
            'file'        => 'glossaire-roman-de-renart-meon-vol3.jpg',
            'internal'    => true,
            'language'    => 'fr',
            'name'        => 'Roman de Renart / Méon v.3',
            'search'      => array(
                'properties' => array(
                    'needTcaf'   => true,
                    'needTobler' => true,
                )
            ),
            'title'       => 'Méon 3',
            'url'         => 'glossaire-roman-de-renart-meon-vol3',
        ),

        'renart-meon-4' => array(
            'description' => 'Glossaire du Roman de Renart, volume 4, M. D. M. Méon, 1826',
            'file'        => 'glossaire-roman-de-renart-meon-vol4.jpg',
            'internal'    => true,
            'language'    => 'fr',
            'name'        => 'Roman de Renart / Méon v.4',
            'search'      => array(
                'properties' => array(
                    'needTcaf'   => true,
                    'needTobler' => true,
                )
            ),
            'title'       => 'Méon 4',
            'url'         => 'glossaire-roman-de-renart-meon-vol4',
        ),

        'roland' => array(
            'description' => 'Glossaire de la Chanson de Roland, Joseph Bédier, 1927',
            'file'        => 'glossaire-chanson-de-roland.jpg',
            'internal'    => true,
            'language'    => 'fr',
            'name'        => 'Chanson de Roland',
            'search'      => array(
                'properties' => array(
                    'needTcaf'   => true,
                    'needTobler' => true,
                )
            ),
            'title'       => 'Roland',
            'url'         => 'glossaire-chanson-de-roland',
        ),

        'rose' => array(
            'description' => 'Glossaire du Roman de la Rose, Ernest Langlois, 1914-1924',
            'file'        => 'glossaire-roman-de-la-rose.jpg',
            'internal'    => true,
            'language'    => 'fr',
            'name'        => 'Roman de la Rose',
            'search'      => array(
                'properties' => array(
                    'needTcaf'   => true,
                    'needTobler' => true,
                )
            ),
            'title'       => 'Rose',
            'url'         => 'glossaire-roman-de-la-rose',
        ),

        'tcaf' => array(
            'description' => "Tableaux de conjugaison de l'ancien français, Machio Okada et Hitoshi Ogurisu, 2007-2011",
            'file'        => 'tableaux-de-conjugaison.jpg',
            'internal'    => true,
            'language'    => 'fr',
            'name'        => 'TCAF',
            'search'      => array(
                'class' => 'Model_Search_Tcaf',
            ),
            'title'       => 'Conjugaisons',
            'url'         => 'tableaux-de-conjugaison',
        ),

        'tristan' => array(
            'description' => 'Glossaire du Roman de Tristan par Béroul, Ernest Muret, 1903',
            'file'        => 'glossaire-roman-de-tristan.jpg',
            'internal'    => true,
            'language'    => 'fr',
            'name'        => 'Roman de Tristan',
            'search'      => array(
                'properties' => array(
                    'needTcaf'   => true,
                    'needTobler' => true,
                )
            ),
            'title'       => 'Tristan',
            'url'         => 'glossaire-roman-de-tristan',
        ),

        'vandaele' => array(
            'description' => "Petit dictionnaire de l'ancien français, Hilaire Van Daele, 1901",
            'file'        => 'vandaele.jpg',
            'internal'    => true,
            'language'    => 'fr',
            'name'        => 'Van Daele',
            'search'      => array(
                'properties' => array(
                    'needGhostwords' => true,
                    'needTcaf'       => true,
                    'needTobler'     => true,
                )
            ),
        ),

        'whitaker' => array(
            'description'  => 'Dictionnaire latin-anglais de William Whitaker',
            'introduction' => 'http://lysy2.archives.nd.edu/cgi-bin/words.exe',
            'language'     => 'la',
            'name'         => 'Whitaker',
            'search'       => 'http://lysy2.archives.nd.edu/cgi-bin/words.exe?',
            // to: en
        ),

        'webster' => array(
            'description'  => "Webster's Revised Unabridged Dictionary (1913, 1828) of the ARTFL project",
            'introduction' => 'http://machaut.uchicago.edu/websters',
            'language'     => 'en',
            'name'         => 'Webster',
            'search'       => 'http://machaut.uchicago.edu/?action=search&resource=Webster%27s&quicksearch=on&word=',
        ),

        'wiktionary' => array(
            'description'  => 'Wiktionnaire, le dictionnaire libre by wiktionary.org',
            'introduction' => 'http://en.wiktionary.org',
            'language'     => 'en',
            'name'         => 'Wiktionary',
            'search'       => 'http://en.wiktionary.org/wiki/',
        ),

        'wiktionnaire' => array(
            'description'  => 'Wiktionary, the free dictionary de wiktionary.org',
            'introduction' => 'http://fr.wiktionary.org',
            'language'     => 'fr',
            'name'         => 'Wiktionnaire',
            'search'       => 'http://fr.wiktionary.org/wiki/',
        ),
    ),

    // domain subpath set in .htaccess, ex. "dicfro" from "www.micmap.org/dicfro"
    'domain-subpath' => $domainSubpath,

    // dictionary groups, displayed as they are ordered below in the home page and select box
    'groups' => array(
        array(
            'name' => 'English',
            'dictionaries' => array(
                'webster',
                'wiktionary',
            ),
        ),
        array(
            'name' => 'Français',
            'dictionaries' => array(
                'cnrtl',
                'leconjugueur',
                'littre',
                'dmf',
                'dvlf',
                'wiktionnaire',
            ),
        ),
        array(
            'name' => 'Français (ancien)',
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
        ),
        array(
            'name' => 'Latin',
            'dictionaries' => array(
                'ducange',
                'gaffiot',
                'jeanneau',
                'whitaker',
            ),
        ),
    ),

    // HTML content directory
    'views-dir' => realpath("$applicationDir/View/scripts"),
);