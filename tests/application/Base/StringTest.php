<?php
/**
 * Dicfro
 *
 * PHP 5
 *
 * @category   Application
 * @package    DicFro
 * @subpackage Tests
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2010 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

require_once "PHPUnit/Framework/TestCase.php";
require_once "PHPUnit/Framework/TestSuite.php";

require_once 'Base/String.php';

/**
 * String class tests
 *
 * @category   Application
 * @package    DicFro
 * @subpackage Tests
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2010 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class StringTest extends PHPUnit_Framework_TestCase
{
    /**
     * The String class instance
     * @var object
     */
    public $string;

    /**
     * Prepares a test
     */
    protected function setUp()
    {
        $this->string = new Base_String();
    }

    /**
     * Tests expandLigature
     */
    public function testExpandLigature()
    {
        $this->assertSame(
            'AE',
            $this->string->expandLigature('Æ'),
            'expanding ligature AE');

        /**********/

        $this->assertSame(
            'OE',
            $this->string->expandLigature('Œ'),
            'expanding ligature OE');
    }

    /**
     * Tests internalToUtf8
     */
    public function testInternalToUtf8()
    {
        $this->assertSame(
            'À',
            $this->string->internalToUtf8("\xc0", 'ISO-8859-1'), // À
            'converting from iso to UTF8');

        /**********/

        $this->assertSame(
            '0',
            $this->string->internalToUtf8('0'),
            'converting from internal to UTF8');
    }

    /**
     * Tests isDos
     */
    public function testIsDos()
    {
        $this->assertTrue(
            $this->string->isDos('win', 'cli'),
            'determining is dos shell #1');

        /**********/

        $this->assertTrue(
            $this->string->isDos('WIN', 'CLI'),
            'determining is dos shell #2');

        /**********/

        $this->assertFalse(
            $this->string->isDos('xyz', 'cli'),
            'determining is not dos shell #1');

        /**********/

        $this->assertFalse(
            $this->string->isDos('win', 'xyz'),
            'determining is not dos shell #2');
    }

    /**
     * Tests removeAccents
     */
    public function testRemoveAccents()
    {
        $this->assertSame(
                                         'AAAACEEEEIIIINOOOOUUUUYAAEEIIOOUU',
            $this->string->removeAccents('ÁÀÂÄÇÉÈÊËÍÌÎÏÑÓÒÔÖÚÙÛÜŸĀĂĒĔĪĬŌŎŪŬ'),
            'removing accents');
    }

    /**
     * Tests toUpper
     */
    public function testToUpper()
    {
        $this->assertSame(
                                   'ABCDEFGHIJKLMNOPQRSTUVWXYZÁÀÂÄÇÉÈÊËÍÌÎÏÑÓÒÔÖÚÙÛÜŸĀĂĒĔĪĬŌŎŪŬ',
            $this->string->toUpper('abcdefghijklmnopqrstuvwxyzáàâäçéèêëíìîïñóòôöúùûüÿāăēĕīĭōŏūŭ'),
            'converting to upper case');
    }

    /**
     * Tests utf8toASCII
     * @depends testToUpper
     * @depends testExpandLigature
     * @depends testRemoveAccents
     */
    public function testUtf8toASCII()
    {
        $this->assertSame(
                                       'AAAACEEEEIIIINOOOOUUUUYAAEEIIOOUUAE',
            $this->string->utf8toASCII('áàâäçéèêëíìîïñóòôöúùûüÿāăēĕīĭōŏūŭÆ0123456789,?;.:/!§%*'),
            'converting UTF8 to ASCII');
    }

    /**
     * Tests toLatin
     * @depends testUtf8toASCII
     */
    public function testToLatin()
    {
        $this->assertSame(
                                   'AAAACEEEEIIIINOOOOVVVVYAAEEIIOOVVIVIV',
            $this->string->toLatin('áàâäçéèêëíìîïñóòôöúùûüÿāăēĕīĭōŏūŭjuiv'),
            'converting to latin characters');
    }

    /**
     * Tests utf8toASCIIorDigit
     * @depends testUtf8toASCII
     */
    public function testUtf8toASCIIorDigit()
    {
        $this->assertSame(
                                              'AAAACEEEEIIIINOOOOUUUUYAAEEIIOOUUAE23456789*-',
            $this->string->utf8toASCIIorDigit('áàâäçéèêëíìîïñóòôöúùûüÿāăēĕīĭōŏūŭÆ0123456789,?;.:/!§%*-'),
            'converting UTF8 to ASCII or digits');
    }

    /**
     * Tests utf8ToInternalString
     */
    public function testUtf8ToInternalString()
    {
        $this->assertSame(
                                                "\x80a",
            $this->string->utf8ToInternalString('Ça', null, true),
            'converting UTF8 to DOS characters');

        /**********/

        $this->assertSame(
                                                "\xc7a",
            $this->string->utf8ToInternalString('Ça', 'ISO-8859-1', false),
            'converting UTF8 to ISO-8859-1');

        /**********/

        $this->assertSame(
                                                '0',
            $this->string->utf8ToInternalString('0', null, false),
            'converting UTF8 to internal');
    }

    /**
     * Tests utf8ToInternal
     * @depends testUtf8ToInternalString
     */
    public function testUtf8ToInternal()
    {
        $this->assertSame(
                                          'abc',
            $this->string->utf8ToInternal('abc'),
            'converting UTF8 string to internal string');

        /**********/

        $this->assertSame(
                                          array('abc', 'def'),
            $this->string->utf8ToInternal(array('abc', 'def')),
            'converting UTF8 string to internal string');
    }

    /**
     * Tests dash2CamelCase
     */
    public function testDash2CamelCase()
    {
        $this->assertSame(
            'abcDefGhi',
            $this->string->dash2CamelCase('abc-def-ghi'),
            'Converting to camel string');
    }
}
