<?php

function checkFolder(string $dir, bool $inc) {
    if($inc)
        return true;

    if (file_exists($dir)) {
        require_once $dir;
        return true;
    }

    return false;
}

$inc = false;
foreach (\Helpers\Helper::listFolder(PATH_HOME . "vendor/conn") as $lib)
    $inc = checkFolder(PATH_HOME . VENDOR . "{$lib}/dash/{$_SESSION['userlogin']['setor']}/menu.php", $inc);

if(DEV)
    $inc = checkFolder(PATH_HOME . "dash/{$_SESSION['userlogin']['setor']}/menu.php", $inc);

if(!$inc) {
    $menu = new \Dashboard\Menu();
    echo $menu->getMenu();
}