<?php
ob_start();
require_once './_config/config.php';
require_once './vendor/autoload.php';

foreach (\Helpers\Helper::listFolder(PATH_HOME . "themes") as $theme) {
    $content = file_get_contents(PATH_HOME . "themes/{$theme}");
    $f = fopen(PATH_HOME . "themes/" . str_replace('w3-', '', $theme), "w+");
    fwrite($f, str_replace('.w3-', '.', $content));
    fclose($f);
    unlink(PATH_HOME . "themes/{$theme}");
}