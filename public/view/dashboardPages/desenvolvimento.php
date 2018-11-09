<?php

$tpl = new \Helpers\Template("dashboard");
$read = new \ConnCrud\Read();

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

$dados['dominio'] = VENDOR . "dashboard/";
$dados['version'] = VERSION;


//Reautorar Conteúdo
$dados['reautor'] = "";
$read->exeRead("usuarios", "ORDER BY setor,nivel,nome DESC LIMIT 50");
if ($read->getResult()) {
    foreach ($read->getResult() as $log)
        $dados['reautor'] .= "<option value='{$log['id']}'>{$log['nome']}</option>";
}

$tpl = new \Helpers\Template("dashboard");
$data['data'] = $tpl->getShow("desenvolvimento", $dados);