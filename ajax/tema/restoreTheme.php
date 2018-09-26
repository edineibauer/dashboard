<?php
$assets = DEV ? "assetsPublic" : "assets";
if(file_exists(PATH_HOME . "{$assets}/theme/theme-recovery.min.css")) {
    $prev = file_get_contents(PATH_HOME . "{$assets}/theme/theme-recovery.min.css");
    $atual = file_get_contents(PATH_HOME . "{$assets}/theme/theme.min.css");

    $f = fopen(PATH_HOME . "{$assets}/theme/theme.min.css", "w+");
    fwrite($f, $prev);
    fclose($f);

    $f = fopen(PATH_HOME . "{$assets}/theme/theme-recovery.min.css", "w+");
    fwrite($f, $atual);
    fclose($f);

    unlink(PATH_HOME . "{$assets}/core.min.css");

    $data['data'] = "ok";

} else {
    $data['data'] = "no";
}