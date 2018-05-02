<?php
$field = strtoupper(trim(strip_tags(filter_input(INPUT_POST, "field", FILTER_DEFAULT))));
$value = trim(strip_tags(filter_input(INPUT_POST, "value", FILTER_DEFAULT)));

if(\Helpers\Check::isJson($value) && preg_match('/url\":/i', $value))
    $value = str_replace('\\', '/', json_decode($value, true)[0]['url']);

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
