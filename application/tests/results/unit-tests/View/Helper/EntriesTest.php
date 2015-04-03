<?php
return array (
  0 =>
  array (
    'method' => 'calculate_entry_hash',
    'args' => 'def',
    'result' => '214229345',
  ),
  1 =>
  array (
    'method' => 'getEntries',
    'status' => 'not-tested',
  ),
  2 =>
  array (
    'method' => 'getEntries',
    'status' => 'not-tested',
  ),
  3 =>
  array (
    'method' => 'getEntries',
    'status' => 'not-tested',
  ),
  4 =>
  array (
    'method' => 'getEntries',
    'status' => 'not-tested',
  ),
  5 =>
  array (
    'method' => 'getSelectedAsciiWord',
    'args' =>
    array (
      0 =>
      array (
      ),
      1 => 'abc',
    ),
    'comment' => 'no entries',
    'result' => 0,
  ),
  6 =>
  array (
    'method' => 'getSelectedAsciiWord',
    'args' =>
    array (
      0 =>
      array (
        0 =>
        array (
          'ascii' => 'ABC',
        ),
        1 =>
        array (
          'ascii' => 'DEF',
        ),
      ),
      1 => 'xyz',
    ),
    'comment' => 'none selected',
    'result' => 0,
  ),
  7 =>
  array (
    'method' => 'getSelectedAsciiWord',
    'args' =>
    array (
      0 =>
      array (
        0 =>
        array (
          'ascii' => 'ABC',
        ),
        1 =>
        array (
          'ascii' => 'CA',
        ),
        2 =>
        array (
          'ascii' => 'GHI',
        ),
      ),
      1 => 'ça',
    ),
    'comment' => 'one selected',
    'result' => 1,
  ),
  8 =>
  array (
    'method' => 'getSelectedExactWord',
    'args' =>
    array (
      0 =>
      array (
      ),
      1 => 'abc',
    ),
    'comment' => 'no entries',
    'result' => 0,
  ),
  9 =>
  array (
    'method' => 'getSelectedExactWord',
    'args' =>
    array (
      0 =>
      array (
        0 =>
        array (
          'original' => 'abc',
        ),
        1 =>
        array (
          'original' => 'def',
        ),
      ),
      1 => 'xyz',
    ),
    'comment' => 'none selected',
    'result' => 0,
  ),
  10 =>
  array (
    'method' => 'getSelectedExactWord',
    'args' =>
    array (
      0 =>
      array (
        0 =>
        array (
          'original' => 'abc',
        ),
        1 =>
        array (
          'original' => 'one TWO ça',
        ),
        2 =>
        array (
          'original' => 'def',
        ),
      ),
      1 => 'ONE two ça',
    ),
    'comment' => 'one selected',
    'result' => 1,
  ),
  11 =>
  array (
    'method' => 'getSelectedHash',
    'args' =>
    array (
      0 =>
      array (
      ),
      1 => 'abc',
    ),
    'comment' => 'no entries',
    'result' => 0,
  ),
  12 =>
  array (
    'method' => 'getSelectedHash',
    'args' =>
    array (
      0 =>
      array (
        0 =>
        array (
          'original' => 'abc',
        ),
        1 =>
        array (
          'original' => 'def',
        ),
      ),
      1 => 'xyz',
    ),
    'comment' => 'none selected',
    'result' => 0,
  ),
  13 =>
  array (
    'method' => 'getSelectedHash',
    'args' =>
    array (
      0 =>
      array (
        0 =>
        array (
          'original' => 'abc',
        ),
        1 =>
        array (
          'original' => 'def',
        ),
        2 =>
        array (
          'original' => 'ghi',
        ),
      ),
      1 => '214229345',
    ),
    'comment' => 'one selected',
    'result' => 1,
  ),
  14 =>
  array (
    'method' => 'getSelectedLikeWord',
    'args' =>
    array (
      0 =>
      array (
      ),
      1 => 'abc',
    ),
    'comment' => 'no entries',
    'result' => 0,
  ),
  15 =>
  array (
    'method' => 'getSelectedLikeWord',
    'args' =>
    array (
      0 =>
      array (
        0 =>
        array (
          'ascii' => 'ABC',
        ),
        1 =>
        array (
          'ascii' => 'DEF',
        ),
      ),
      1 => 'xyz',
    ),
    'comment' => 'none selected',
    'result' => 0,
  ),
  16 =>
  array (
    'method' => 'getSelectedLikeWord',
    'args' =>
    array (
      0 =>
      array (
        0 =>
        array (
          'ascii' => 'ABC',
        ),
        1 =>
        array (
          'ascii' => 'CARPET',
        ),
        2 =>
        array (
          'ascii' => 'GHI',
        ),
      ),
      1 => 'ça',
    ),
    'comment' => 'one selected',
    'result' => 1,
  ),
  17 =>
  array (
    'method' => 'getSelectedWord',
    'args' =>
    array (
      0 =>
      array (
        0 =>
        array (
          'original' => 'abc',
        ),
        1 =>
        array (
          'original' => 'one TWO ça',
        ),
        2 =>
        array (
          'original' => 'def',
        ),
      ),
      1 => 'ONE two ça',
    ),
    'comment' => 'same word',
    'result' => 1,
  ),
  18 =>
  array (
    'method' => 'getSelectedWord',
    'args' =>
    array (
      0 =>
      array (
        0 =>
        array (
          'ascii' => 'ABC',
          'original' => 'abc',
        ),
        1 =>
        array (
          'ascii' => 'CA',
          'original' => 'ca',
        ),
        2 =>
        array (
          'ascii' => 'DEF',
          'original' => 'def',
        ),
      ),
      1 => 'ça',
    ),
    'comment' => 'same ASCII',
    'result' => 1,
  ),
  19 =>
  array (
    'method' => 'getSelectedWord',
    'args' =>
    array (
      0 =>
      array (
        0 =>
        array (
          'ascii' => 'ABC',
          'original' => 'abc',
        ),
        1 =>
        array (
          'ascii' => 'CARPET',
          'original' => 'carpet',
        ),
        2 =>
        array (
          'ascii' => 'DEF',
          'original' => 'def',
        ),
      ),
      1 => 'ça',
    ),
    'comment' => 'same begining',
    'result' => 1,
  ),
  20 =>
  array (
    'method' => 'getSelectedWord',
    'args' =>
    array (
      0 =>
      array (
        0 =>
        array (
          'ascii' => 'ABC',
          'original' => 'abc',
        ),
        1 =>
        array (
          'ascii' => 'DEF',
          'original' => 'def',
        ),
      ),
      1 => 'xyz',
    ),
    'comment' => 'none selected',
    'result' => 0,
  ),
  21 =>
  array (
    'method' => 'setOption',
    'args' =>
    array (
      0 =>
      array (
        'original' => 'def',
        'page' => 123,
        'volume' => 9,
      ),
    ),
    'comment' => 'not selected',
    'result' =>
    array (
      'selected' => NULL,
      'text' => 'def',
      'value' => '123/9/214229345',
    ),
  ),
  22 =>
  array (
    'method' => 'setOption',
    'args' =>
    array (
      0 =>
      array (
        'original' => 'def',
        'page' => 123,
        'volume' => 9,
      ),
      1 => true,
    ),
    'comment' => 'selected',
    'result' =>
    array (
      'selected' => true,
      'text' => 'def',
      'value' => '123/9/214229345',
    ),
  ),
  23 =>
  array (
    'method' => 'setOptions',
    'args' =>
    array (
      0 =>
      array (
        0 =>
        array (
          'original' => 'abc',
          'page' => 123,
          'volume' => 8,
        ),
        1 =>
        array (
          'original' => 'def',
          'page' => 456,
          'volume' => 9,
        ),
      ),
    ),
    'comment' => 'new',
    'result' =>
    array (
      0 =>
      array (
        'selected' => false,
        'text' => 'abc',
        'value' => '123/8/891568578',
      ),
      1 =>
      array (
        'selected' => false,
        'text' => 'def',
        'value' => '456/9/214229345',
      ),
    ),
  ),
  24 =>
  array (
    'method' => 'setOptions',
    'args' =>
    array (
      0 =>
      array (
        0 =>
        array (
          'original' => 'abc',
          'page' => 123,
          'volume' => 8,
        ),
        1 =>
        array (
          'original' => 'def',
          'page' => 456,
          'volume' => 9,
        ),
      ),
      1 =>
      array (
        0 =>
        array (
          'selected' => NULL,
          'text' => 'ijk',
          'value' => '111/22/333333333',
        ),
        1 =>
        array (
          'selected' => NULL,
          'text' => 'lmn',
          'value' => '444/55/666666666',
        ),
      ),
      2 => 1,
    ),
    'comment' => 'added and selected',
    'result' =>
    array (
      0 =>
      array (
        'selected' => NULL,
        'text' => 'ijk',
        'value' => '111/22/333333333',
      ),
      1 =>
      array (
        'selected' => NULL,
        'text' => 'lmn',
        'value' => '444/55/666666666',
      ),
      2 =>
      array (
        'selected' => false,
        'text' => 'abc',
        'value' => '123/8/891568578',
      ),
      3 =>
      array (
        'selected' => true,
        'text' => 'def',
        'value' => '456/9/214229345',
      ),
    ),
  ),
);