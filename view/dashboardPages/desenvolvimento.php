<?php

$tpl = new \Helpers\Template("dashboard");
$routesAll = [DOMINIO];
foreach (\Helpers\Helper::listFolder(PATH_HOME . VENDOR) as $item)
    $routesAll[] = $item;

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

$dados['dominio'] = DOMINIO === "dashboard" ? "" : VENDOR . "dashboard/";
$dados['version'] = VERSION;

$tpl = new \Helpers\Template("dashboard");
$data['data'] = $tpl->getShow("desenvolvimento", $dados);