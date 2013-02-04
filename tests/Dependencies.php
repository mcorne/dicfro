<?php
/**
 * Dicfro
 *
 * PHP 5
 *
 * @category   DicFro
 * @package    Tests
 * @subpackage Tests
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2010 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

/**
 * Class dependencies
 */

return array(
    // 'Base/Application.php' => array(), TODO: fix
    'Base/String.php' => array(),
    'Base/View.php' => array(
        'View/Helper/Dictionaries.php',
        'View/Helper/Images.php',
        'View/Helper/Verbs.php',
        'View/Helper/Words.php',
    ),

    'Controller/Front.php' => array(
        'Base/String.php',
        'Base/View.php',
        ),
    'Controller/Interface.php' => array(
        'Base/String.php',
        'Controller/Front.php',
        'Base/View.php',
    ),

    // 'Model/Parser.php' => array(), // TODO: add
    // 'Model/Parser/Chretien.php' => array(),
    // 'Model/Parser/Couronnement.php' => array(),
    // 'Model/Parser/Gaffiot.php' => array(),
    // 'Model/Parser/GaffiotLike.php' => array(),
    // 'Model/Parser/Gdf.php' => array(),
    // 'Model/Parser/GdfLike.php' => array(),
    // 'Model/Parser/Gdfc.php' => array(),
    // 'Model/Parser/Gdflex.php' => array(),
    // 'Model/Parser/Ghostwords.php' => array(),
    // 'Model/Parser/Lexromv.php' => array(),
    // 'Model/Parser/Roland.php' => array(),
    // 'Model/Parser/Rose.php' => array(),
    // 'Model/Parser/Tcaf.php' => array(),
    // 'Model/Parser/Tobler.php' => array(),
    // 'Model/Parser/Tristan.php' => array(),
    // 'Model/Parser/Vandaele.php' => array(),
    // 'Model/Parser/Whitaker.php' => array(),

    'Model/Query.php' => array('Base/String.php'),
    'Model/Query/Generic.php' => array('Model/Query.php'),
    'Model/Query/Ghostwords.php' => array('Model/Query.php'),
    'Model/Query/Tcaf.php' => array('Model/Query.php'),
    'Model/Query/Tobler.php' => array('Model/Query.php'),
    'Model/Query/Whitaker.php' => array('Model/Query.php'),

    'Model/Search/Generic.php' => array(
        // 'Model/Search.php',
        'Model/Query/Generic.php',
        'Model/Query/Ghostwords.php',
        'Model/Query/Tcaf.php',
        'Model/Query/Tobler.php',
        'Model/Query/Whitaker.php',
    ),
    'Model/Search/Jeanneau.php' => array(
        'Base/String.php',
        // 'Model/Search.php',
    ),
    'Model/Search/Tcaf.php' => array(
        'Model/Query/Tcaf.php',
        // 'Model/Search.php',
    ),

    'View/Helper/Base.php' => array('Base/String.php'),
    'View/Helper/Dictionaries.php' => array('View/Helper/Base.php'),
    'View/Helper/Images.php' => array('View/Helper/Base.php'),
    'View/Helper/Verbs.php' => array('View/Helper/Base.php'),
    'View/Helper/Words.php' => array('View/Helper/Base.php'),
);