<?php
return array (
  0 =>
  array (
    'method' => 'countDictionaries',
    'properties' =>
    array (
      'view->config' =>
      array (
        'dictionaries' =>
        array (
          'century' =>
          array (
          ),
          'chambers' =>
          array (
          ),
          'godefroy' =>
          array (
          ),
        ),
      ),
    ),
    'result' => 3,
  ),
  1 =>
  array (
    'method' => 'countDictionariesByLanguage',
    'properties' =>
    array (
      'view->config' =>
      array (
        'dictionaries' =>
        array (
          'century' =>
          array (
            'description' => 'this is the Century...',
            'name' => 'Century',
            'type' => 'index',
          ),
          'chambers' =>
          array (
            'name' => 'Chambers',
            'description' => 'this is the Chambers...',
            'type' => 'external',
            'url' => 'http://century.com',
          ),
          'godefroy' =>
          array (
            'name' => 'Godefroy',
            'description' => 'voici le Godefroy...',
            'description-en' => 'this is the Godefroy',
            'type' => 'internal',
          ),
        ),
        'groups' =>
        array (
          0 =>
          array (
            'dictionaries' =>
            array (
              0 => 'century',
              1 => 'chambers',
            ),
            'language' => 'en',
          ),
          1 =>
          array (
            'dictionaries' =>
            array (
              0 => 'godefroy',
            ),
            'language' => 'fr',
          ),
        ),
      ),
    ),
    'result' =>
    array (
      'en' => 2,
      'fr' => 1,
    ),
  ),
  2 =>
  array (
    'method' => 'countDictionariesByType',
    'properties' =>
    array (
      'view->config' =>
      array (
        'dictionaries' =>
        array (
          'century' =>
          array (
            'type' => 'index',
          ),
          'chambers' =>
          array (
            'type' => 'index',
          ),
          'godefroy' =>
          array (
            'type' => 'internal',
          ),
        ),
      ),
    ),
    'result' =>
    array (
      'index' => 2,
      'internal' => 1,
    ),
  ),
  3 =>
  array (
    'method' => 'getDictionaryDescription',
    'properties' =>
    array (
      'view->dictionary' =>
      array (
        'description' => 'this is the Century...',
      ),
    ),
    'comment' => 'has description',
    'result' => 'this is the Century...',
  ),
  4 =>
  array (
    'method' => 'getDictionaryDescription',
    'comment' => 'no description',
    'result' => NULL,
  ),
  5 =>
  array (
    'method' => 'getNewDictionaries',
    'properties' =>
    array (
      'view->config' =>
      array (
        'dictionaries' =>
        array (
          'century' =>
          array (
            'created' => '2000-01-01',
          ),
        ),
      ),
    ),
    'comment' => 'not new',
    'result' =>
    array (
    ),
  ),
  6 =>
  array (
    'method' => 'getNewDictionaries',
    'properties' =>
    array (
      'view->config' =>
      array (
        'dictionaries' =>
        array (
          'chambers' =>
          array (
            'created' => '2030-01-01',
            'name' => 'Chambers',
            'description' => 'this is the Chambers...',
            'type' => 'index',
          ),
        ),
      ),
    ),
    'comment' => 'new addition',
    'result' =>
    array (
      0 =>
      array (
        'text' => 'Chambers',
        'title' => 'this is the Chambers...',
        'type' => 'index',
        'value' => 'chambers',
      ),
    ),
  ),
  7 =>
  array (
    'method' => 'getNewDictionaries',
    'properties' =>
    array (
      'view->config' =>
      array (
        'dictionaries' =>
        array (
          'godefroy' =>
          array (
            'created' => '2000-01-01',
            'name' => 'Godefroy',
            'description' => 'this is the Godefroy...',
            'type' => 'internal',
            'updated' => '2030-01-01',
            'url' => 'http://godefroy.com',
          ),
        ),
      ),
    ),
    'comment' => 'new update',
    'result' =>
    array (
      0 =>
      array (
        'text' => 'Godefroy',
        'title' => 'this is the Godefroy...',
        'type' => 'internal',
        'value' => 'http://godefroy.com',
      ),
    ),
  ),
  8 =>
  array (
    'method' => 'getPageTitle',
    'properties' =>
    array (
      'view->dictionary' =>
      array (
        'title' => 'Century',
      ),
    ),
    'result' => 'Century',
  ),
  9 =>
  array (
    'method' => 'getPageTitle',
    'properties' =>
    array (
      'view->dictionary' =>
      array (
        'name' => 'Chambers',
      ),
    ),
    'result' => 'Chambers',
  ),
  10 =>
  array (
    'method' => 'groupDictionaries',
    'args' => 'chambers',
    'properties' =>
    array (
      'view->config' =>
      array (
        'dictionaries' =>
        array (
          'century' =>
          array (
            'description' => 'this is the Century...',
            'name' => 'Century',
            'type' => 'index',
          ),
          'chambers' =>
          array (
            'name' => 'Chambers',
            'description' => 'this is the Chambers...',
            'type' => 'external',
            'url' => 'http://century.com',
          ),
          'godefroy' =>
          array (
            'name' => 'Godefroy',
            'description' => 'voici le Godefroy...',
            'description-en' => 'this is the Godefroy',
            'type' => 'internal',
          ),
        ),
        'groups' =>
        array (
          0 =>
          array (
            'dictionaries' =>
            array (
              0 => 'century',
              1 => 'chambers',
            ),
            'language' => 'en',
          ),
          1 =>
          array (
            'dictionaries' =>
            array (
              0 => 'godefroy',
            ),
            'language' => 'fr',
          ),
        ),
      ),
      'view->languages' =>
      array (
        'en' =>
        array (
          'english' => 'English',
          'original' => 'English',
        ),
        'fr' =>
        array (
          'english' => 'French',
          'original' => 'Français',
        ),
      ),
    ),
    'comment' => 'original language',
    'result' =>
    array (
      0 =>
      array (
        'label' => 'English',
        'language' => 'en',
        'list-title' => 'English',
        'options' =>
        array (
          0 =>
          array (
            'list-title' => NULL,
            'selected' => false,
            'text' => 'Century',
            'title' => 'this is the Century...',
            'type' => 'index',
            'value' => 'century',
          ),
          1 =>
          array (
            'list-title' => NULL,
            'selected' => true,
            'text' => 'Chambers',
            'title' => 'this is the Chambers...',
            'type' => 'external',
            'value' => 'http://century.com',
          ),
        ),
      ),
      1 =>
      array (
        'label' => 'Français',
        'language' => 'fr',
        'list-title' => 'French',
        'options' =>
        array (
          0 =>
          array (
            'list-title' => 'this is the Godefroy',
            'selected' => false,
            'text' => 'Godefroy',
            'title' => 'voici le Godefroy...',
            'type' => 'internal',
            'value' => 'godefroy',
          ),
        ),
      ),
    ),
  ),
  11 =>
  array (
    'method' => 'groupDictionaries',
    'args' =>
    array (
      0 => 'godefroy',
      1 => true,
    ),
    'properties' =>
    array (
      'view->config' =>
      array (
        'dictionaries' =>
        array (
          'century' =>
          array (
            'description' => 'this is the Century...',
            'name' => 'Century',
            'type' => 'index',
          ),
          'chambers' =>
          array (
            'name' => 'Chambers',
            'description' => 'this is the Chambers...',
            'type' => 'external',
            'url' => 'http://century.com',
          ),
          'godefroy' =>
          array (
            'name' => 'Godefroy',
            'description' => 'voici le Godefroy...',
            'description-en' => 'this is the Godefroy',
            'type' => 'internal',
          ),
        ),
        'groups' =>
        array (
          0 =>
          array (
            'dictionaries' =>
            array (
              0 => 'century',
              1 => 'chambers',
            ),
            'language' => 'en',
          ),
          1 =>
          array (
            'dictionaries' =>
            array (
              0 => 'godefroy',
            ),
            'language' => 'fr',
          ),
        ),
      ),
      'view->languages' =>
      array (
        'en' =>
        array (
          'english' => 'English',
          'original' => 'English',
        ),
        'fr' =>
        array (
          'english' => 'French',
          'original' => 'Français',
        ),
      ),
    ),
    'comment' => 'in english',
    'result' =>
    array (
      0 =>
      array (
        'label' => 'English',
        'language' => 'en',
        'list-title' => 'English',
        'options' =>
        array (
          0 =>
          array (
            'list-title' => NULL,
            'selected' => false,
            'text' => 'Century',
            'title' => 'this is the Century...',
            'type' => 'index',
            'value' => 'century',
          ),
          1 =>
          array (
            'list-title' => NULL,
            'selected' => false,
            'text' => 'Chambers',
            'title' => 'this is the Chambers...',
            'type' => 'external',
            'value' => 'http://century.com',
          ),
        ),
      ),
      1 =>
      array (
        'label' => 'French',
        'language' => 'fr',
        'list-title' => 'Français',
        'options' =>
        array (
          0 =>
          array (
            'list-title' => 'voici le Godefroy...',
            'selected' => true,
            'text' => 'Godefroy',
            'title' => 'this is the Godefroy',
            'type' => 'internal',
            'value' => 'godefroy',
          ),
        ),
      ),
    ),
  ),
);