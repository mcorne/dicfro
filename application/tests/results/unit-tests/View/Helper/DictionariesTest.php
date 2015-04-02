<?php
return array (
  0 =>
  array (
    'method' => 'countDictionaries',
    'status' => 'not-tested',
  ),
  1 =>
  array (
    'method' => 'countDictionariesByLanguage',
    'status' => 'not-tested',
  ),
  2 =>
  array (
    'method' => 'countDictionariesByType',
    'status' => 'not-tested',
  ),
  3 =>
  array (
    'method' => 'getDictionaryDescription',
    'properties' =>
    array (
      'view->dictionary' =>
      array (
        'description' => 'abc',
      ),
    ),
    'comment' => 'has description',
    'result' => 'abc',
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
    'status' => 'not-tested',
  ),
  6 =>
  array (
    'method' => 'getPageTitle',
    'status' => 'not-tested',
  ),
  7 =>
  array (
    'method' => 'groupDictionaries',
    'args' => 'chambers',
    'properties' =>
    array (
      'view->config' =>
      array (
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
  8 =>
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