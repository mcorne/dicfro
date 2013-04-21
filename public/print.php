<?php
/**
 * Dicfro
 *
 * PHP 5
 *
 * @author    Michel Corne <mcorne@yahoo.com>
 * @copyright 2008-2013 Michel Corne
 * @license   http://www.opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>DicFro Print</title>
    <link rel="stylesheet" type="text/css" href="interface.css" />
  </head>

  <body>
    <?=isset($_GET['content']) ? html_entity_decode(base64_decode($_GET['content'], true)) : null;?>
  </body>

  <script type="text/javascript">window.print();</script>
</html>