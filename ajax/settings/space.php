<?php

$key = strip_tags(trim(filter_input(INPUT_POST, 'key', FILTER_DEFAULT)));

$file = file_get_contents(PATH_HOME . "_config/config.php");
if (preg_match('/spacekey/i', $file)) {
    $keyOld = explode("'", explode("'SPACEKEY', '", $file)[1])[0];
    $file = str_replace($keyOld, $key, $file);
} else {
    $file = str_replace("<?php", "<?php\ndefine('SPACEKEY', '{$key}');", $file);
}

$f = fopen(PATH_HOME . "_config/config.php", "w+");
fwrite($f, $file);
fclose($f);
