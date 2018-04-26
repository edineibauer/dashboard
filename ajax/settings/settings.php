<?php
$tpl = new \Helpers\Template("dashboard");
$read = new \ConnCrud\Read();
$dados['reautor'] = "";
$dados['dominio'] = DEV && DOMINIO === "dashboard" ? "" : "vendor/conn/dashboard/";
$dados['version'] = VERSION;

$read->exeRead("usuarios", "ORDER BY setor,nivel,nome DESC LIMIT 50");
if ($read->getResult()) {
    foreach ($read->getResult() as $log)
        $dados['reautor'] .= "<option value='{$log['id']}'>{$log['nome']}</option>";
}

$data['data'] = $tpl->getShow('settings', $dados);