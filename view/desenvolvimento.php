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
$dados['permissao'] = "";
$dados['version'] = VERSION;

$menuNotShow = \Helpers\Check::getMenuNotAllow();
$setorAllow = \Helpers\Check::getEntityNotAllow();
$entitys = [];
$tpl = new \Helpers\Template("dashboard");
$dic = new \EntityForm\Dicionario("usuarios");
$allow = $dic->search('column', 'setor')->getAllow();
$allow['values'] = array_merge(["0"], $allow['values']);
$allow['names'] = array_merge(["Anônimos"], $allow['names']);

foreach (\Helpers\Helper::listFolder(PATH_HOME . "entity/cache") as $item) {
    if ($item !== "info" && $item !== "login_attempt.json" && preg_match('/.json$/i', $item)) {
        $entitys[] = str_replace('.json', "", $item);
    }
}

foreach ($allow['values'] as $i => $setor) {
    $dados['permissao'] .= $tpl->getShow("list-allow-session",
        [
            "value" => $setor,
            "nome" => $allow['names'][$i],
            "entitys" => $entitys,
            "allow" => $setorAllow[$setor] ?? null,
            "allowMenu" => $menuNotShow[$setor] ?? null
        ]);
}


$data = $tpl->getShow("dev", $dados);