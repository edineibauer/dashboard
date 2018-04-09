<?php

use \Helpers\Check;

$tpl = new \Helpers\Template("dashboard");
$notAllowCreateLogged = file_exists(PATH_HOME . "_config/create_entity_not_allow_logged.json") ? json_decode(file_get_contents(PATH_HOME . "_config/create_entity_not_allow_logged.json"), true) : null;

$tpl->show("menu-li", ["icon" => "timeline", "title" => "Dashboard", "attr" => "", "lib" => ""]);

if ($_SESSION['userlogin']['setor'] === '1')
    echo "<a href='" . HOME . "entidades' target='_blank' class='btn-entity hover-theme bar-item button z-depth-0 padding'><i class='material-icons left padding-right'>accessibility</i><span class='left'>Gerenciar Entidades</span></a>";

foreach (\Helpers\Helper::listFolder(PATH_HOME . "entity/cache") as $item) {
    $entity = str_replace('.json', '', $item);
    if ((empty($notAllowCreateLogged[$_SESSION['userlogin']['setor']]) || !in_array($entity, $notAllowCreateLogged[$_SESSION['userlogin']['setor']])) && preg_match('/\.json$/i', $item) && $item !== "login_attempt.json" && $item !== "info") {
        $dados['lib'] = "";
        $dados['attr'] = $entity;
        $dados['icon'] = 'account_balance_wallet';
        $dados['title'] = ucwords(trim(str_replace(['-', '_'], [' ', ' '], $entity)));
        $tpl->show("menu-li", $dados);
    }
}

foreach (\Helpers\Helper::listFolder(PATH_HOME . "vendor/conn") as $lib) {
    if (file_exists(PATH_HOME . "vendor/conn/{$lib}/menu/menu.json")) {
        $incMenu = json_decode(file_get_contents(PATH_HOME . "vendor/conn/{$lib}/menu/menu.json"), true);
        if(!isset($incMenu['setor']) || empty($incMenu['setor']) || $incMenu['setor'] >= $_SESSION['userlogin']['setor']) {
            $incMenu = [
                'lib' => $lib,
                'title' => ucwords(Check::words(trim(strip_tags($incMenu['title'])), 3)),
                'icon' => Check::words(trim(strip_tags($incMenu['icon'])), 1),
                'attr' => Check::words(trim(strip_tags($incMenu['attr'])), 1)
            ];

            $tpl->show("menu-li", $incMenu);
        }
    }
}

if(DEV) {
    if (file_exists(PATH_HOME . "menu/menu.json")) {
        $incMenu = json_decode(file_get_contents(PATH_HOME . "menu/menu.json"), true);
        if(!isset($incMenu['setor']) || empty($incMenu['setor']) || $incMenu['setor'] >= $_SESSION['userlogin']['setor']) {
            $incMenu = [
                'lib' => DOMINIO,
                'title' => ucwords(Check::words(trim(strip_tags($incMenu['title'])), 3)),
                'icon' => Check::words(trim(strip_tags($incMenu['icon'])), 1),
                'attr' => Check::words(trim(strip_tags($incMenu['attr'])), 1)
            ];

            $tpl->show("menu-li", $incMenu);
        }
    }
}

$tpl->show("menu-li", ["icon" => "settings", "title" => "Configurações", "attr" => "", "lib" => ""]);