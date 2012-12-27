<?php

/**
 * Dicfro
 *
 * PHP 5
 *
 * @category  DicFro
 * @package   Config
 * @author    Michel Corne <mcorne@yahoo.com>
 * @copyright 2008-2012 Michel Corne
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

/**
 * Application configuration file
 */

// sets domain subpath
$domainSubpath = '';          // default installation
// $domainSubpath = 'dicfro'; // production installation with dicfro as domain subpath

$applicationDir = dirname(__FILE__); // global variable used by scripts

return array(
    // data directory containing indexes and databases
    'data-dir' => realpath("$applicationDir/data"),

    // dictionaries details
    'dictionaries' => array(
        'chretien' => array(
            'description' => "Dictionnaire de l'oeuvre de Chrétien de Troyes, Wörterbuch zu Kristian von Troyes' sämtlichen Werken, Wendelin Foerster, 1914",
            'file' => 'dictionnaire-chretien-de-troyes.jpg',
            'internal' => true,
            'language' => 'fr',
            'name' => 'Chrétien de Troyes',
            'search' => array(
                'properties' => array(
                    'needTcaf' => true,
                    'needTobler' => true,
                )
            ),
            'source' => 'http://galatea.univ-tlse2.fr/pictura/UtpicturaServeur/Images/NePasOuvrir/0/A0709.jpg',
            'title' => 'Chrétien',
            'url' => 'glossaire-chretien-de-troyes',
        ),

        'cnrtl' => array(
            'description' => 'Dictionnaire du Centre National de Ressources Textuelles et Lexicales',
            'introduction' => 'http://www.cnrtl.fr/definition/',
            'language' => 'fr',
            'name' => 'CNRTL',
            'search' => 'http://www.cnrtl.fr/definition/',
        ),

        'cotgrave' => array(
            'description' => 'A Dictionarie of the French and English Tongues, Randle Cotgrave, 1611',
            'introduction' => 'http://www.pbm.com/~lindahl/cotgrave/',
            'language' => 'fr',
            'name' => 'Cotgrave [Eng]',
            'search' => 'http://www.pbm.com/~lindahl/cotgrave/search/search_backend.cgi?query=',
        ),

        'couronnement' => array(
            'description' => 'Glossaire du Couronnement de Louis, Ernest Langlois, 1888',
            'file' => 'glossaire-couronnement-de-louis.jpg',
            'internal' => true,
            'language' => 'fr',
            'name' => 'Couronnement de Louis',
            'search' => array(
                'properties' => array(
                    'needTcaf' => true,
                    'needTobler' => true,
                )
            ),
            'source' => 'http://histoireenprimaire.free.fr/images/sacre_st_louis.jpg',
            'title' => 'Couronnement',
            'url' => 'glossaire-couronnement-de-louis',
        ),

        'dmf' => array(
            'description' => "Dictionnaire du Moyen Français par l'Atilf",
            'introduction' => 'http://www.atilf.fr/dmf/',
            'language' => 'fr',
            'name' => 'Moyen français',
            'search' => 'http://www.atilf.fr/dmf/definition/',
            'title' => 'DMF',
        ),

        'dvlf' => array(
            'description' => "Dictionnaire vivant de la langue française du projet ARTFL",
            'introduction' => 'http://dvlf.uchicago.edu/',
            'language' => 'fr',
            'name' => 'DVLF',
            'search' => 'http://dvlf.uchicago.edu/mot/',
        ),

        'gaffiot' => array(
            'description' => 'Dictionnaire Latin-Français, Félix Gaffiot, 1934',
            'file' => 'gaffiot.jpg',
            'internal' => true,
            'language' => 'la',
            'name' => 'Gaffiot [Fra]',
            'search' => array(
                'properties' => array(
                    'needWhitaker' => true,
                )
            ),
            'source' => 'http://multimedia.fnac.com/multimedia/images_produits/ZoomPE/6/5/6/9782011667656.jpg',
        ),

        'gdf' => array(
            'description' => "Dictionnaire de l'ancienne langue française et de tous ses dialectes du IXème au XVème siècle, Frédéric Godefroy, 1880-1895",
            'file' => 'dictionnaire-godefroy.jpg',
            'internal' => true,
            'language' => 'fr',
            'name' => 'Godefroy - Dictionnaire',
            'search' => array(
                'properties' => array(
                    'errataFiles' => 'dictionary/gdf/mPimg-3/%s-%s[a-z]*/*.gif',
                    'imagePath' => 'dictionary/gdf/mImg/%s.gif',
                    'needErrataImages' => true,
                    'needGhostwords' => true,
                    'needTcaf' => true,
                    'needTobler' => true,
                )
            ),
            'source' => 'http://upload.wikimedia.org/wikipedia/commons/a/a4/Old_book_bindings_cropped.jpg',
            'title' => 'D. Godefroy',
            'url' => 'dictionnaire-godefroy',
        ),

        'gdfc' => array(
            'description' => "Complément du dictionnaire de l'ancienne langue française et de tous ses dialectes du IXème au XVème siècle, Frédéric Godefroy, 1895-1902",
            'file' => 'complement-godefroy.jpg',
            'internal' => true,
            'language' => 'fr',
            'name' => 'Godefroy - Complément',
            'search' => array(
                'properties' => array(
                    'digit' => 1,
                    'errataFiles' => 'dictionary/gdfc/mPimg-3/%s-%s[a-z]*/*.gif',
                    'imagePath' => 'dictionary/gdfc/mImg/%s.gif',
                    'needErrataImages' => true,
                    'needGhostwords' => true,
                    'needTcaf' => true,
                    'needTobler' => true,
                )
            ),
            'source' => 'http://farm1.static.flickr.com/137/327471676_7557f4d649.jpg',
            'title' => 'C. Godefroy',
            'url' => 'complement-godefroy',
        ),

        'gdflex' => array(
            'description' => "Lexique de l'ancien français, Frédéric Godefroy, 1901",
            'file' => 'lexique-godefroy.jpg',
            'internal' => true,
            'language' => 'fr',
            'name' => 'Godefroy - Lexique',
            'query' => array(
                'properties' => array(
                    'extraColumns' => ', errata',
                ),
            ),
            'search' => array(
                'properties' => array(
                    'needErrataText' => true,
                    'needGhostwords' => true,
                    'needTcaf' => true,
                    'needTobler' => true,
                )
            ),
            'source' => 'http://www.voilieraventures.com/photos/old%20book%206.gif',
            'title' => 'L. Godefroy',
            'url' => 'lexique-godefroy',
        ),

        'jeanneau' => array(
            'description' => 'Dictionnaire français-latin de Gérard Jeanneau',
            'introduction' => 'http://www.prima-elementa.fr/Dico.htm',
            'language' => 'la',
            'name' => 'Jeanneau [Fra]',
            'search' => array(
                'class' => 'Model_Search_Jeanneau',
            ),
        ),

        'leconjugueur' => array(
            'description' => 'La conjugaison française par le Conjugueur',
            'introduction' => 'http://www.leconjugueur.com/',
            'language' => 'fr',
            'name' => 'Le conjugueur',
            'search' => 'http://www.leconjugueur.com/php5/index.php?v=',
        ),

        'lexromv' => array(
            'description' => "Lexique Roman ou dictionnaire de la langue des troubadours comparée avec les autres langues de l'Europe latine, François J. M. Raynouard, 1844",
            'file' => 'lexique-roman.jpg',
            'internal' => true,
            'introduction' => 'roman.phtml',
            'language' => 'fr',
            'name' => 'Lexique Roman',
            'source' => 'http://www.histoiredesjuifs.com/images/1230%20Susskind_von_Trimberg.jpg',
            'title' => 'L. Roman',
            'url' => 'lexique-roman',
        ),

        'littre' => array(
            'description' => "Dictionnaire de la langue française d'Émile Littré, 1872-1877",
            'introduction' => 'http://francois.gannaz.free.fr/Littre/',
            'language' => 'fr',
            'name' => 'Littré',
            'search' => 'http://francois.gannaz.free.fr/Littre/xmlittre.php?requete=',
        ),

        'renart-fhs' => array(
            'description' => 'Glossaire du Roman de Renart, N. Fukumoto, N. Harano et S. Suzuki, 1985',
            'file' => 'glossaire-roman-de-renart-fhs.jpg',
            'internal' => true,
            'language' => 'fr',
            'name' => 'Roman de Renart / FHS',
            'search' => array(
                'properties' => array(
                    'needTcaf' => true,
                    'needTobler' => true,
                )
            ),
            'source' => 'http://www.espritdepicardie.com/files/romanderenartXIIIeme_0.jpg',
            'title' => 'Renart',
            'url' => 'glossaire-roman-de-renart-fhs',
        ),

        'renart-meon-1' => array(
            'description' => 'Glossaire du Roman de Renart, volume 1, M. D. M. Méon, 1826',
            'file' => 'glossaire-roman-de-renart-meon-vol1.jpg',
            'internal' => true,
            'language' => 'fr',
            'name' => 'Roman de Renart / Méon v.1',
            'search' => array(
                'properties' => array(
                    'needTcaf' => true,
                    'needTobler' => true,
                )
            ),
            'source' => 'http://books.google.fr/books?id=WR72lYfyTP8C&hl=fr&pg=PR4-IA4#v=onepage&q&f=false',
            'title' => 'Méon 1',
            'url' => 'glossaire-roman-de-renart-meon-vol1',
        ),

        'renart-meon-2' => array(
            'description' => 'Glossaire du Roman de Renart, volume 2, M. D. M. Méon, 1826',
            'file' => 'glossaire-roman-de-renart-meon-vol2.jpg',
            'internal' => true,
            'language' => 'fr',
            'name' => 'Roman de Renart / Méon v.2',
            'search' => array(
                'properties' => array(
                    'needTcaf' => true,
                    'needTobler' => true,
                )
            ),
            'source' => 'http://upload.wikimedia.org/wikipedia/commons/c/c2/Roman_de_Renart.jpg',
            'title' => 'Méon 2',
            'url' => 'glossaire-roman-de-renart-meon-vol2',
        ),

        'renart-meon-3' => array(
            'description' => 'Glossaire du Roman de Renart, volume 3, M. D. M. Méon, 1826',
            'file' => 'glossaire-roman-de-renart-meon-vol3.jpg',
            'internal' => true,
            'language' => 'fr',
            'name' => 'Roman de Renart / Méon v.3',
            'search' => array(
                'properties' => array(
                    'needTcaf' => true,
                    'needTobler' => true,
                )
            ),
            'source' => 'http://books.google.fr/books?id=OpgCAAAAYAAJ&hl=fr&pg=PP10#v=onepage&q&f=false',
            'title' => 'Méon 3',
            'url' => 'glossaire-roman-de-renart-meon-vol3',
        ),

        'renart-meon-4' => array(
            'description' => 'Glossaire du Roman de Renart, volume 4, M. D. M. Méon, 1826',
            'file' => 'glossaire-roman-de-renart-meon-vol4.jpg',
            'internal' => true,
            'language' => 'fr',
            'name' => 'Roman de Renart / Méon v.4',
            'search' => array(
                'properties' => array(
                    'needTcaf' => true,
                    'needTobler' => true,
                )
            ),
            'source' => 'http://books.google.fr/books?id=FNUTAAAAQAAJ&hl=fr&pg=PP8#v=onepage&q&f=false',
            'title' => 'Méon 4',
            'url' => 'glossaire-roman-de-renart-meon-vol4',
        ),

        'roland' => array(
            'description' => 'Glossaire de la Chanson de Roland, Joseph Bédier, 1927',
            'file' => 'glossaire-chanson-de-roland.jpg',
            'internal' => true,
            'language' => 'fr',
            'name' => 'Chanson de Roland',
            'search' => array(
                'properties' => array(
                    'needTcaf' => true,
                    'needTobler' => true,
                )
            ),
            'source' => 'http://www.hs-augsburg.de/~harsch/gallica/Chronologie/11siecle/Roland/rol_pict.jpg',
            'title' => 'Roland',
            'url' => 'glossaire-chanson-de-roland',
        ),

        'rose' => array(
            'description' => 'Glossaire du Roman de la Rose, Ernest Langlois, 1914-1924',
            'file' => 'glossaire-roman-de-la-rose.jpg',
            'internal' => true,
            'language' => 'fr',
            'name' => 'Roman de la Rose',
            'search' => array(
                'properties' => array(
                    'needTcaf' => true,
                    'needTobler' => true,
                )
            ),
            'source' => 'http://expositions.bnf.fr/livres/rose/index.htm',
            'title' => 'Rose',
            'url' => 'glossaire-roman-de-la-rose',
        ),

        'tcaf' => array(
            'description' => "Tableaux de conjugaison de l'ancien français, Machio Okada et Hitoshi Ogurisu, 2007-2011",
            'file' => 'tableaux-de-conjugaison.jpg',
            'internal' => true,
            'language' => 'fr',
            'name' => 'TCAF',
            'search' => array(
                'class' => 'Model_Search_Tcaf',
            ),
            'source' => 'http://ant_deus.pagesperso-orange.fr/veillesurmoi/verbes.jpg',
            'title' => 'Conjugaisons',
            'url' => 'tableaux-de-conjugaison',
        ),

        'tristan' => array(
            'description' => 'Glossaire du Roman de Tristan par Béroul, Ernest Muret, 1903',
            'file' => 'glossaire-roman-de-tristan.jpg',
            'internal' => true,
            'language' => 'fr',
            'name' => 'Roman de Tristan',
            'search' => array(
                'properties' => array(
                    'needTcaf' => true,
                    'needTobler' => true,
                )
            ),
            'source' => 'http://beaujarret.fiftiz.fr/blog/images/b/e/beaujarret/121896783993.jpeg',
            'title' => 'Tristan',
            'url' => 'glossaire-roman-de-tristan',
        ),

        'vandaele' => array(
            'description' => "Petit dictionnaire de l'ancien français, Hilaire Van Daele, 1901",
            'file' => 'vandaele.jpg',
            'internal' => true,
            'language' => 'fr',
            'name' => 'Van Daele',
            'search' => array(
                'properties' => array(
                    'needGhostwords' => true,
                    'needTcaf' => true,
                    'needTobler' => true,
                )
            ),
            'source' => 'http://www.fleuron-du-cuir.com/images-ok/23-restauration-livre-ancien-03.jpg',
        ),

        'whitaker' => array(
            'description' => 'Dictionnaire latin-anglais de William Whitaker',
            'introduction' => 'http://lysy2.archives.nd.edu/cgi-bin/words.exe',
            'language' => 'la',
            'name' => 'Whitaker [Eng]',
            'search' => 'http://lysy2.archives.nd.edu/cgi-bin/words.exe?',
        ),

        'webster' => array(
            'description' => "Webster's Revised Unabridged Dictionary (1913, 1828) of the ARTFL project",
            'introduction' => 'http://machaut.uchicago.edu/websters',
            'language' => 'en',
            'name' => 'Webster',
            'search' => 'http://machaut.uchicago.edu/?action=search&resource=Webster%27s&quicksearch=on&word=',
        ),

        'wiktionary' => array(
            'description' => 'Wiktionnaire, le dictionnaire libre by wiktionary.org',
            'introduction' => 'http://en.wiktionary.org',
            'language' => 'en',
            'name' => 'Wiktionary',
            'search' => 'http://en.wiktionary.org/wiki/',
        ),

        'wiktionnaire' => array(
            'description' => 'Wiktionary, the free dictionary de wiktionary.org',
            'introduction' => 'http://fr.wiktionary.org',
            'language' => 'fr',
            'name' => 'Wiktionnaire',
            'search' => 'http://fr.wiktionary.org/wiki/',
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
                'cotgrave',
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
                'roland',
                'chretien',
                'couronnement',
                'gdf',
                'gdfc',
                'gdflex',
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
                'gaffiot',
                'jeanneau',
                'whitaker',
            ),
        ),
    ),

    // HTML content directory
    'views-dir' => realpath("$applicationDir/View/scripts"),
);