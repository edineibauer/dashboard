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


/**
 * @param string $menuDir
 * @param string $dir
 * @param array $file
 * @return array
 */
function addNotShow(string $menuDir, string $dir, array $file): array
{
    foreach (\Helpers\Helper::listFolder($menuDir . "entity/{$dir}") as $item) {
        $m = json_decode(file_get_contents($menuDir . "entity/{$dir}/{$item}"), true);
        foreach ($m as $setor => $info) {
            foreach ($info as $entity) {
                if (file_exists($menuDir . "entity/cache/{$entity}.json")) {
                    if ($setor === "*") {
                        foreach (array_keys($file) as $setor2) {
                            //Adiciona entidade ao setor
                            if (!in_array($entity, $file[$setor2]))
                                $file[$setor2][] = $entity;
                        }
                    } else {
                        //Adiciona entidade ao setor
                        if (!in_array($entity, $file[$setor]))
                            $file[$setor][] = $entity;
                    }
                }
            }
        }
    }

    return $file;
}

/**
 * Retorna as Entidades que não devem aparecer no menu
 * @return array
 */
function readMenuNotShow(): array
{
    $file = [];
    if (file_exists(PATH_HOME . "_config/menu_not_show.json"))
        $file = json_decode(file_get_contents(PATH_HOME . "_config/menu_not_show.json"), true);

    if (DEV && file_exists(PATH_HOME . "entity/menu"))
        $file = addNotShow(PATH_HOME, 'menu', $file);

    foreach (\Helpers\Helper::listFolder(PATH_HOME . "vendor/conn") as $lib) {
        if (file_exists(PATH_HOME . "vendor/conn/{$lib}/entity/menu"))
            $file = addNotShow(PATH_HOME . "vendor/conn/{$lib}/", 'menu', $file);
    }

    return $file;
}

/**
 * Retorna as Entidades que não devem ser editadas por setor de usuário
 * @return array
 */
function readEntityNotShow(): array
{
    $file = [];
    if (file_exists(PATH_HOME . "_config/entity_not_show.json"))
        $file = json_decode(file_get_contents(PATH_HOME . "_config/entity_not_show.json"), true);

    if (DEV && file_exists(PATH_HOME . "entity/allow"))
        $file = addNotShow(PATH_HOME, 'allow', $file);

    foreach (\Helpers\Helper::listFolder(PATH_HOME . "vendor/conn") as $lib) {
        if (file_exists(PATH_HOME . "vendor/conn/{$lib}/entity/allow"))
            $file = addNotShow(PATH_HOME . "vendor/conn/{$lib}/", 'allow', $file);
    }

    return $file;
}

$menuNotShow = readMenuNotShow();
$setorAllow = readEntityNotShow();
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


$data['data']['content'] = $tpl->getShow("dev", $dados);