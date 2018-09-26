<?php

use MatthiasMullie\Minify;

$txt = trim(strip_tags(filter_input(INPUT_POST, "txt", FILTER_DEFAULT)));

$assets = DEV ? "assetsPublic" : "assets";

//Cria backup
copy(PATH_HOME . "{$assets}/theme/theme.min.css", PATH_HOME . "{$assets}/theme/theme-recovery.min.css");
//Cria novo theme
$mini = new Minify\CSS($txt);
$mini->minify(PATH_HOME . $assets . "/theme/theme.min.css");
//Remove atual CSS
unlink(PATH_HOME . $assets . "/core.min.css");

//Atualiza a VERSION
$conf = file_get_contents(PATH_HOME . "_config/config.php");
$version = explode("')", explode("'VERSION', '", $conf)[1])[0];
$newVersion = $version + 0.01;
$conf = str_replace("'VERSION', '{$version}')", "'VERSION', '{$newVersion}')", $conf);
$f = fopen(PATH_HOME . "_config/config.php", "w");
fwrite($f, $conf);
fclose($f);

//Sincroniza txt de versão
$f = fopen(PATH_HOME . "_config/updates/version.txt", "w+");
fwrite($f, file_get_contents(PATH_HOME . "composer.lock"));
fclose($f);

//Update Manifest
$theme = explode("}", explode(".theme{", file_get_contents(PATH_HOME . "assets" . (DEV ? "Public" : "") . "/theme/theme.min.css"))[1])[0];
$themeBack = explode("!important", explode("background-color:", $theme)[1])[0];
$themeColor = explode("!important", explode("color:", $theme)[1])[0];
$content = str_replace(['{$sitename}', '{$favicon}', '{$theme}', '{$themeColor}'], [SITENAME, FAVICON, $themeBack, $themeColor], file_get_contents(PATH_HOME . "vendor/conn/config/tpl/manifest.txt"));

$fp = fopen(PATH_HOME . "manifest.json", "w+");
fwrite($fp, $content);
fclose($fp);

$data['data'] = "ok";