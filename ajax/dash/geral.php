<?php

$tpl = new \Helpers\Template("dashboard");
$dados['dominio'] = DEV && DOMINIO === "dashboard" ? "" : "vendor/conn/dashboard/";
$dados['version'] = VERSION;

$read = new \ConnCrud\Read();
$read->exeRead("dashboard_note", "ORDER BY id LIMIT 16");
$dados['note'] = $read->getResult() ?? [];
if(!empty($dados['note'])) {
    $dataTime = new \Helpers\DateTime();
    foreach ($dados['note'] as $i => $item)
        $dados['note'][$i]['data'] = $dataTime->getDateTime($item['data'], 'd \d\e M \d\e Y');
}

$data['data'] = $tpl->getShow('dashboard', $dados);