<?php

$key = filter_input(INPUT_POST, 'route', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

$file = "[";

foreach ($key as $i => $item)
    $file .= ($i > 0 ? ',' : '') . '"' . $item . '"';
$file .= "]";

$f = fopen(PATH_HOME . "_config/route.json", "w+");
fwrite($f, $file);
fclose($f);
