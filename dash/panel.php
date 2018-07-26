<?php
$tpl = new \Helpers\Template("dashboard");
$dados['dominio'] = DEV && DOMINIO === "dashboard" ? "" : "vendor/conn/dashboard/";
$dados['version'] = VERSION;
$dados['dashboard'] = "";

if(!empty($_SESSION['userlogin']['setor'])) {
    foreach (\Helpers\Helper::listFolder(PATH_HOME . "vendor/conn") as $lib) {
        if (file_exists(PATH_HOME . "vendor/conn/{$lib}/dash/{$_SESSION['userlogin']['setor']}.php")) {
            ob_start();
            include_once PATH_HOME . "vendor/conn/{$lib}/dash/{$_SESSION['userlogin']['setor']}.php";
            $dados['dashboard'] = ob_get_contents();
            ob_end_clean();
            break;
        }
    }
    if (DEV && empty($dados['dashboard']) && file_exists(PATH_HOME . "dash/{$_SESSION['userlogin']['setor']}.php")) {
        ob_start();
        include_once PATH_HOME . "dash/{$_SESSION['userlogin']['setor']}.php";
        $dados['dashboard'] = ob_get_contents();
        ob_end_clean();
    }

    $read = new \ConnCrud\Read();
    $read->exeRead("dashboard_note", "WHERE autor = :a ORDER BY id LIMIT 16", "a={$_SESSION['userlogin']['id']}");
    $dados['note'] = $read->getResult() ?? [];
    if (!empty($dados['note'])) {
        $dataTime = new \Helpers\DateTime();
        foreach ($dados['note'] as $i => $item)
            $dados['note'][$i]['data'] = $dataTime->getDateTime($item['data'], 'd \d\e M \d\e Y');
    }
}


$data['data'] = $tpl->getShow('dashboard', $dados);