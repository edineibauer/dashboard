<?php
$txt = trim(strip_tags(filter_input(INPUT_POST, "txt", FILTER_DEFAULT)));

$assets = DEV ? "assetsPublic" : "assets";
copy(PATH_HOME . "{$assets}/theme/theme.css", PATH_HOME . "{$assets}/theme/theme-recovery.css");

$f = fopen(PATH_HOME . "{$assets}/theme/theme.css", "w+");
fwrite($f, $txt);
fclose($f);

// Remove comments
$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $txt);
// Remove space after colons
$buffer = str_replace(': ', ':', $buffer);
// Remove whitespace
$buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);

$f = fopen(PATH_HOME . "{$assets}/theme/theme.min.css", "w+");
fwrite($f, $buffer);
fclose($f);