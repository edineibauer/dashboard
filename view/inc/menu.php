<?php

use \Helpers\Check;

/**
 * @param string $menuDir
 * @param array $notShowMenuEntity
 * @return array
 */
function addMenuNotShow(string $menuDir, array $notShowMenuEntity): array
{
    foreach (\Helpers\Helper::listFolder($menuDir . "entity/menu") as $menu) {
        $m = json_decode(file_get_contents($menuDir . "entity/menu/{$menu}"), true);
        foreach (["*", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10"] as $nivel) {
            if (!empty($m[$nivel])) {
                foreach ($m[$nivel] as $entity) {
                    if (file_exists($menuDir . "entity/cache/{$entity}.json")) {
                        if ($nivel === "*") {
                            for ($i = 1; $i < 10; $i++)
                                $notShowMenuEntity[$i][] = $entity;
                        } else {
                            $notShowMenuEntity[$nivel][] = $entity;
                        }
                    }
                }
            }
        }
    }

    return $notShowMenuEntity;
}

/**
 * Retorna as Entidades que não devem aparecer no menu
 * @return array
 */
function readMenuNotShow() :array
{
    $notShowMenuEntity = ["1" => [], "2" => [], "3" => [], "4" => [], "5" => [], "6" => [], "7" => [], "8" => [], "9" => [], "10" => []];
    if (DEV && file_exists(PATH_HOME . "entity/menu"))
        $notShowMenuEntity = addMenuNotShow(PATH_HOME, $notShowMenuEntity);

    foreach (\Helpers\Helper::listFolder(PATH_HOME . "vendor/conn") as $lib) {
        if (file_exists(PATH_HOME . "vendor/conn/{$lib}/entity/menu"))
            $notShowMenuEntity = addMenuNotShow(PATH_HOME . "vendor/conn/{$lib}/", $notShowMenuEntity);
    }
    return $notShowMenuEntity;
}

/**
 * Mostra Menu
 * @param array $incMenu
 */
function showMenuOption(array $incMenu) {
    if(!empty($incMenu)){
        $tpl = new \Helpers\Template("dashboard");
        foreach ($incMenu as $menu) {
            if(empty($menu['setor']) || $menu['setor'] >= $_SESSION['userlogin']['setor']) {
                $menu = [
                    'lib' => Check::words(trim(strip_tags($menu['lib'])), 1),
                    'file' => Check::words(trim(strip_tags($menu['file'])), 1),
                    'title' => ucwords(Check::words(trim(strip_tags($menu['title'])), 3)),
                    'icon' => Check::words(trim(strip_tags($menu['icon'])), 1)
                ];

                $tpl->show("menu-li", $menu);
            }
        }
    }
}

$setor = $_SESSION['userlogin']['setor'];
$tpl = new \Helpers\Template("dashboard");
$notShowMenuEntity = readMenuNotShow();


//Menu Dashboard Geral
$tpl->show("menu-li", ["icon" => "timeline", "title" => "Dashboard", "file" => "dash/geral", "lib" => "dashboard"]);

//Editor de Entidades para Adm
if ($setor === '1')
    echo "<a href='" . HOME . "entidades' target='_blank' class='btn-entity hover-theme bar-item button z-depth-0 padding'><i class='material-icons left padding-right'>accessibility</i><span class='left'>Gerenciar Entidades</span></a>";

//Opção para cada entidade
foreach (\Helpers\Helper::listFolder(PATH_HOME . "entity/cache") as $item) {
    $entity = str_replace('.json', '', $item);
    if ((empty($notShowMenuEntity[$setor]) || !in_array($entity, $notShowMenuEntity[$setor])) && preg_match('/\.json$/i', $item) && $item !== "login_attempt.json" && $item !== "info") {
        $dados['lib'] = "";
        $dados['file'] = $entity;
        $dados['icon'] = 'account_balance_wallet';
        $dados['title'] = ucwords(trim(str_replace(['-', '_'], [' ', ' '], $entity)));
        $tpl->show("menu-li", $dados);
    }
}

//Menu de lib
foreach (\Helpers\Helper::listFolder(PATH_HOME . "vendor/conn") as $lib) {
    if (file_exists(PATH_HOME . "vendor/conn/{$lib}/dashboard/menu.json")) {
        $incMenu = json_decode(file_get_contents(PATH_HOME . "vendor/conn/{$lib}/dashboard/menu.json"), true);
        showMenuOption($incMenu);
    }
}

//Menu Dev
if(DEV) {
    if (file_exists(PATH_HOME . "dashboard/menu.json")) {
        $incMenu = json_decode(file_get_contents(PATH_HOME . "dashboard/menu.json"), true);
        showMenuOption($incMenu);
    }
}