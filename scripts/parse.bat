@rem
@rem Dicfro
@rem
@rem Dictionary parsing
@rem
@rem PHP 5
@rem
@rem @author    Michel Corne <mcorne@yahoo.com>
@rem @copyright 2008-2012 Michel Corne
@rem @license   http://www.opensource.org/licenses/gpl-3.0.html GNU GPL v3
@rem

@rem examples:
@rem parse dictionary  : search vandaele
@rem verbose mode      : search vandaele 1
@rem from a given line : search vandaele 0 10
@rem line count        : search vandaele 0 10 50

@php parse.php %1 %2 %3 %4 %5