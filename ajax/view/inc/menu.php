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
    $inc = checkFolder(PATH_HOME . "vendor/conn/{$lib}/ajax/view/inc/menu-{$_SESSION['userlogin']['setor']}.php", $inc);

if(DEV)
    $inc = checkFolder(PATH_HOME . "ajax/view/inc/menu-{$_SESSION['userlogin']['setor']}.php", $inc);

if(!$inc) {
    $menu = new \Dashboard\Menu();
    echo $menu->getMenu();
}