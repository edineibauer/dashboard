<?php
if(file_exists(PATH_HOME . "assetsPublic/theme-recovery.min.css")) {
    $prev = file_get_contents(PATH_HOME . "assetsPublic/theme-recovery.min.css");
    $atual = file_get_contents(PATH_HOME . "assetsPublic/theme.min.css");

    $f = fopen(PATH_HOME . "assetsPublic/theme.min.css", "w+");
    fwrite($f, $prev);
    fclose($f);

    $f = fopen(PATH_HOME . "assetsPublic/theme-recovery.min.css", "w+");
    fwrite($f, $atual);
    fclose($f);

    new \Dashboard\UpdateDashboard(['assets']);

    $data['data'] = "ok";

} else {
    $data['data'] = "no";
}