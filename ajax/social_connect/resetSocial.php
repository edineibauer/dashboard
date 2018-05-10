<?php
$field = "INSTAGRAM_TOKEN";
$value = "";
$file = file_get_contents(PATH_HOME . "_config/config.php");
if (preg_match("/\'{$field}\',/i", $file)) {
    $valueOld = explode("'", explode("('{$field}', '", $file)[1])[0];
    $file = str_replace("'{$field}', '{$valueOld}'", "'{$field}', '{$value}'", $file);
} else {
    $file = str_replace("<?php", "<?php\ndefine('{$field}', '{$value}');", $file);
}

$f = fopen(PATH_HOME . "_config/config.php", "w+");
fwrite($f, $file);
fclose($f);
