<?php
$dados['dominio'] = VENDOR . "dashboard/public/";
$dados['version'] = VERSION;

$menuNotShow = \Helpers\Check::getMenuNotAllow();
$setorAllow = \Helpers\Check::getEntityNotAllow();
$tpl = new \Helpers\Template("dashboard");
$dic = new \Entity\Dicionario("usuarios");
$allow = $dic->search('column', 'setor')->getAllow();
$allow['values'] = array_merge(["0"], $allow['values']);
$allow['names'] = array_merge(["Anônimos"], $allow['names']);

$entitys = [];
foreach (\Helpers\Helper::listFolder(PATH_HOME . "entity/cache") as $item) {
    if ($item !== "info" && $item !== "login_attempt.json" && preg_match('/.json$/i', $item)) {
        $entitys[] = str_replace('.json', "", $item);
    }
}

$dados['permissao'] = "";
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

$data['data'] = $tpl->getShow("permissoes", $dados);