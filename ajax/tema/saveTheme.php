<?php
$txt = trim(strip_tags(filter_input(INPUT_POST, "txt", FILTER_DEFAULT)));

$f = fopen(PATH_HOME . "assetsPublic/theme/theme.css", "w+");
fwrite($f, $txt);
fclose($f);