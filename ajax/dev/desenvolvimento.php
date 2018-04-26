<?php

$tpl = new \Helpers\Template("dashboard");
$routesAll = [];
foreach (\Helpers\Helper::listFolder(PATH_HOME . "vendor/conn") as $item)
    $routesAll[] = $item;

if (DEV)
    $routesAll[] = DOMINIO;

$dados['routes'] = json_decode(file_get_contents(PATH_HOME . "_config/route.json"), true);
$dados['routesAll'] = "";
foreach ($routesAll as $item) {
    $dataRoute = [
        "item" => $item,
        "nome" => ucwords(str_replace(["_", "-", "  "], [" ", " ", " "], $item)),
        "value" => in_array($item, $dados['routes']),
        "disable" => in_array($item, ["session-control", "dashboard", "link-control", "entity-form"])
    ];
    $dados['routesAll'] .= $tpl->getShow("checkbox", $dataRoute);
}

$dados['dominio'] = DEV && DOMINIO === "dashboard" ? "" : "vendor/conn/dashboard/";
$dados['file'] = file_get_contents(PATH_HOME . "vendor/conn/link-control/tpl/header.tpl");
$dados['dev'] = explode(";", explode("const ISDEV = ", $dados['file'])[1])[0] === 'true';
$dados['permissao'] = "";
$dados['spacekey'] = defined('SPACEKEY') && !empty(SPACEKEY) ? SPACEKEY : "";
$dados['version'] = VERSION;

$dicLogin = \EntityForm\Metadados::getDicionario("usuarios");
foreach ($dicLogin as $i => $dataLogin) {
    if ($dataLogin['column'] === "setor") {
        $entitys = [];
        foreach (\Helpers\Helper::listFolder(PATH_HOME . "entity/cache") as $item) {
            if ($item !== "info" && $item !== "login_attempt.json" && preg_match('/.json$/i', $item))
                $entitys[] = str_replace('.json', "", $item);
        }

        $notLogged = file_exists(PATH_HOME . "_config/create_entity_allow_anonimos.json") ? json_decode(file_get_contents(PATH_HOME . "_config/create_entity_allow_anonimos.json"), true) : null;
        $logged = file_exists(PATH_HOME . "_config/create_entity_not_allow_logged.json") ? json_decode(file_get_contents(PATH_HOME . "_config/create_entity_not_allow_logged.json"), true) : null;

        $tpl = new \Helpers\Template("dashboard");
        $dados['permissao'] = $tpl->getShow("list-allow-session", ["value" => 0, "nome" => "Anônimo", "entitys" => $entitys, "allow" => $notLogged]);
        foreach ($dataLogin['allow']['values'] as $e => $value)
            $dados['permissao'] .= $tpl->getShow("list-allow-session", ["value" => $value, "nome" => $dataLogin['allow']['names'][$e], "entitys" => $entitys, "allow" => $logged[$value] ?? null]);

        break;
    }
}


$data['data'] = $tpl->getShow("dev", $dados);