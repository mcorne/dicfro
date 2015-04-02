<?php
return array (
  0 =>
  array (
    'method' => 'dash2CamelCase',
    'args' => 'abc-def-ghi',
    'comment' => 'default',
    'result' => 'abcDefGhi',
  ),
  1 =>
  array (
    'method' => 'dash2CamelCase',
    'args' =>
    array (
      0 => 'abc-def-ghi',
      1 => false,
    ),
    'comment' => 'no UC first',
    'result' => 'abcDefGhi',
  ),
  2 =>
  array (
    'method' => 'dash2CamelCase',
    'args' =>
    array (
      0 => 'abc-def-ghi',
      1 => true,
    ),
    'comment' => 'UC first',
    'result' => 'AbcDefGhi',
  ),
  3 =>
  array (
    'method' => 'expandLigature',
    'args' => 'Æ',
    'comment' => 'AE',
    'result' => 'AE',
  ),
  4 =>
  array (
    'method' => 'expandLigature',
    'args' => 'Œ',
    'comment' => 'OE',
    'result' => 'OE',
  ),
  5 =>
  array (
    'method' => 'internalToUtf8',
    'args' => '0',
    'comment' => 'ISO to UTF8',
    'result' => '0',
  ),
  6 =>
  array (
    'method' => 'internalToUtf8',
    'args' =>
    array (
      0 => '�',
      1 => 'ISO-8859-1',
    ),
    'comment' => 'internal to UTF8',
    'result' => 'À',
  ),
  7 =>
  array (
    'method' => 'isDos',
    'args' =>
    array (
      0 => 'win',
      1 => 'cli',
    ),
    'comment' => 'DOS shell lowercase',
    'result' => true,
  ),
  8 =>
  array (
    'method' => 'isDos',
    'args' =>
    array (
      0 => 'WIN',
      1 => 'CLI',
    ),
    'comment' => 'DOS shell uppercase',
    'result' => true,
  ),
  9 =>
  array (
    'method' => 'isDos',
    'args' =>
    array (
      0 => 'xyz',
      1 => 'cli',
    ),
    'comment' => 'bad OS',
    'result' => false,
  ),
  10 =>
  array (
    'method' => 'isDos',
    'args' =>
    array (
      0 => 'win',
      1 => 'xyz',
    ),
    'comment' => 'bad SAPI',
    'result' => false,
  ),
  11 =>
  array (
    'method' => 'removeAccents',
    'args' => 'ÁÀÂÄÇÉÈÊËÍÌÎÏÑÓÒÔÖÚÙÛÜŸĀĂĒĔĪĬŌŎŪŬ',
    0 =>
    array (
      'method' => 'toLatin',
      'args' => 'áàâäçéèêëíìîïñóòôöúùûüÿāăēĕīĭōŏūŭjuiv',
    ),
    'result' => 'AAAACEEEEIIIINOOOOUUUUYAAEEIIOOUU',
  ),
  12 =>
  array (
    'method' => 'toLatin',
    'args' => 'áàâäçéèêëíìîïñóòôöúùûüÿāăēĕīĭōŏūŭjuiv',
    'result' => 'AAAACEEEEIIIINOOOOVVVVYAAEEIIOOVVIVIV',
  ),
  13 =>
  array (
    'method' => 'toLatin',
    'args' => 'áàâäçéèêëíìîïñóòôöúùûüÿāăēĕīĭōŏūŭjuiv',
    'result' => 'AAAACEEEEIIIINOOOOVVVVYAAEEIIOOVVIVIV',
  ),
  14 =>
  array (
    'method' => 'toUpper',
    'args' => 'abcdefghijklmnopqrstuvwxyzáàâäçéèêëíìîïñóòôöúùûüÿāăēĕīĭōŏūŭ',
    'result' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZÁÀÂÄÇÉÈÊËÍÌÎÏÑÓÒÔÖÚÙÛÜŸĀĂĒĔĪĬŌŎŪŬ',
  ),
  15 =>
  array (
    'method' => 'utf8toASCII',
    'args' => 'áàâäçéèêëíìîïñóòôöúùûüÿāăēĕīĭōŏūŭÆ0123456789,?;.:/!§%*',
    'result' => 'AAAACEEEEIIIINOOOOUUUUYAAEEIIOOUUAE',
  ),
  16 =>
  array (
    'method' => 'utf8toASCIIorDigit',
    'args' => 'áàâäçéèêëíìîïñóòôöúùûüÿāăēĕīĭōŏūŭÆ0123456789,?;.:/!§%*-',
    'result' => 'AAAACEEEEIIIINOOOOUUUUYAAEEIIOOUUAE23456789*-',
  ),
  17 =>
  array (
    'method' => 'utf8ToInternal',
    'args' => 'abc',
    'comment' => 'string',
    'result' => 'abc',
  ),
  18 =>
  array (
    'method' => 'utf8ToInternal',
    'args' =>
    array (
      0 =>
      array (
        0 => 'abc',
        1 => 'def',
      ),
    ),
    'comment' => 'array',
    'result' =>
    array (
      0 => 'abc',
      1 => 'def',
    ),
  ),
  19 =>
  array (
    'method' => 'utf8ToInternalString',
    'args' =>
    array (
      0 => 'Ça',
      1 => NULL,
      2 => true,
    ),
    'comment' => 'UTF8 to DOS',
    'result' => '�a',
  ),
  20 =>
  array (
    'method' => 'utf8ToInternalString',
    'args' =>
    array (
      0 => 'Ça',
      1 => 'ISO-8859-1',
      2 => false,
    ),
    'comment' => 'UTF8 to ISO',
    'result' => '�a',
  ),
  21 =>
  array (
    'method' => 'utf8ToInternalString',
    'args' =>
    array (
      0 => '0',
      1 => NULL,
      2 => false,
    ),
    'comment' => 'UTF8 to internal',
    'result' => '0',
  ),
);