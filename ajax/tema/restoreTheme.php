<?php
$assets = DEV ? "assetsPublic" : "assets";
if(file_exists(PATH_HOME . "{$assets}/theme/theme-recovery.css")) {
    $prev = file_get_contents(PATH_HOME . "{$assets}/theme/theme-recovery.css");
    $atual = file_get_contents(PATH_HOME . "{$assets}/theme/theme.css");

    $f = fopen(PATH_HOME . "{$assets}/theme/theme.css", "w+");
    fwrite($f, $prev);
    fclose($f);

    $f = fopen(PATH_HOME . "{$assets}/theme/theme-recovery.css", "w+");
    fwrite($f, $atual);
    fclose($f);
} else {
    $data['data'] = "ok";
}