<?php

$key = filter_input(INPUT_POST, 'key', FILTER_VALIDATE_BOOLEAN);

$file = file_get_contents(PATH_HOME . "vendor/conn/link-control/tpl/header.tpl");
$keyOld = explode(";", explode("const ISDEV = ", $file)[1])[0];
$file = str_replace($keyOld, $key ? "true" : "false", $file);

$f = fopen(PATH_HOME . "vendor/conn/link-control/tpl/header.tpl", "w+");
fwrite($f, $file);
fclose($f);
