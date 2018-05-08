<?php
$txt = trim(strip_tags(filter_input(INPUT_POST, "txt", FILTER_DEFAULT)));

$assets = DEV ? "assetsPublic" : "assets";
copy(PATH_HOME . "{$assets}/theme/theme.css", PATH_HOME . "{$assets}/theme/theme-recovery.css");

$f = fopen(PATH_HOME . "{$assets}/theme/theme.css", "w+");
fwrite($f, $txt);
fclose($f);

$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $txt);
$buffer = str_replace(': ', ':', $buffer);
$buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);

$f = fopen(PATH_HOME . "{$assets}/theme/theme.min.css", "w+");
fwrite($f, $buffer);
fclose($f);

$conf = file_get_contents(PATH_HOME . "_config/config.php");
$version = explode("')", explode("'VERSION', '", $conf)[1])[0];
$newVersion = $version + 0.01;
$conf = str_replace("'VERSION', '{$version}')", "'VERSION', '{$newVersion}')", $conf);
$f = fopen(PATH_HOME . "_config/config.php", "w");
fwrite($f, $conf);
fclose($f);
updateVersionTxt();

$f = fopen(PATH_HOME . "_config/updates/version.txt", "w+");
fwrite($f, file_get_contents(PATH_HOME . "composer.lock"));
fclose($f);