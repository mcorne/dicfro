<?php
/**
 * Dicfro
 *
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2015 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

require_once 'Base/String.php';
require_once 'Model/UnitTest.php';

class StringTest extends Model_UnitTest
{
    public $class = 'Base_String';

    public $testCases = [
        [
            'method'  => 'expandLigature',
            'args'    => 'Æ',
            'comment' => 'AE',
        ],
        [
            'method'  => 'expandLigature',
            'args'    => 'Œ',
            'comment' => 'OE',
        ],
        [
            'method'  => 'internalToUtf8',
            'args'    => '0',
            'comment' => 'ISO to UTF8',
        ],
        [
            'method'  => 'internalToUtf8',
            'args'    => ["\xc0", 'ISO-8859-1'],
            'comment' => 'internal to UTF8',
        ],
        [
            'method'  => 'isDos',
            'args'    => ['win', 'cli'],
            'comment' => 'DOS shell lowercase',
        ],
        [
            'method'  => 'isDos',
            'args'    => ['WIN', 'CLI'],
            'comment' => 'DOS shell uppercase',
        ],
        [
            'method'  => 'isDos',
            'args'    => ['xyz', 'cli'],
            'comment' => 'bad OS',
        ],
        [
            'method'  => 'isDos',
            'args'    => ['win', 'xyz'],
            'comment' => 'bad SAPI',
        ],
        [
            'method'  => 'removeAccents',
            'args'    => 'ÁÀÂÄÇÉÈÊËÍÌÎÏÑÓÒÔÖÚÙÛÜŸĀĂĒĔĪĬŌŎŪŬ',
        ],
        [
            'method'  => 'toUpper',
            'args'    => 'abcdefghijklmnopqrstuvwxyzáàâäçéèêëíìîïñóòôöúùûüÿāăēĕīĭōŏūŭ',
        ],
        [
            'method'  => 'utf8toASCII',
            'args'    => 'áàâäçéèêëíìîïñóòôöúùûüÿāăēĕīĭōŏūŭÆ0123456789,?;.:/!§%*',
        ],
        [
            'method'  => 'toLatin',
            'args'    => 'áàâäçéèêëíìîïñóòôöúùûüÿāăēĕīĭōŏūŭjuiv',
        ],
        [
            'method'  => 'utf8toASCIIorDigit',
            'args'    => 'áàâäçéèêëíìîïñóòôöúùûüÿāăēĕīĭōŏūŭÆ0123456789,?;.:/!§%*-',
        ],
        [
            'method'  => 'utf8ToInternalString',
            'args'    => ['Ça', null, true],
            'comment' => 'UTF8 to DOS',
        ],
        [
            'method'  => 'utf8ToInternalString',
            'args'    => ['Ça', 'ISO-8859-1', false],
            'comment' => 'UTF8 to ISO',
        ],
        [
            'method'  => 'utf8ToInternalString',
            'args'    => ['0', null, false],
            'comment' => 'UTF8 to internal',
        ],
        [
            'method'  => 'utf8ToInternal',
            'args'    => 'abc',
            'comment' => 'string',
        ],
        [
            'method'  => 'utf8ToInternal',
            'args'    => [['abc', 'def']],
            'comment' => 'array',
        ],
        [
            'method'  => 'dash2CamelCase',
            'args'    => 'abc-def-ghi',
            'comment' => 'default',
        ],
        [
            'method'  => 'dash2CamelCase',
            'args'    => ['abc-def-ghi', false],
            'comment' => 'no UC first',
        ],
        [
            'method'  => 'dash2CamelCase',
            'args'    => ['abc-def-ghi', true],
            'comment' => 'UC first',
        ],
    ];
}
