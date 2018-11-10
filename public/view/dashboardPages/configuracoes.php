<?php
$tpl = new \Helpers\Template("dashboard");
$read = new \ConnCrud\Read();

//Garante criação dos dados de configuração caso não exista
$read->exeRead(PRE . "config", "WHERE id=1");
if (!$read->getResult()) {

    $criarData = [
        "nome_do_site" => defined('SITENAME') && !empty(SITENAME) ? SITENAME : "",
        "subtitulo" => defined('SITESUB') && !empty(SITESUB) ? SITESUB : "",
        "descricao" => defined('SITEDESC') && !empty(SITEDESC) ? SITEDESC : "",
        "https" => defined('PROTOCOL') && !empty(PROTOCOL) && PROTOCOL === "https://" ? 1 : 0,
        "www" => defined('WWW') && !empty(WWW) && WWW === "www" ? 1 : 0,
        "logo" => defined('LOGO') && !empty(LOGO) ? '[{"url": "' . LOGO . '", "name": "", "size": 1078, "type": "image/png"}]' : null,
        "favicon" => defined('FAVICON') && !empty(FAVICON) ? '[{"url": "' . FAVICON . '", "name": "", "size": 1078, "type": "image/png"}]' : null,
    ];

    $d = new \Entity\Dicionario("config");
    $d->setData($criarData);
    $d->save();
}

$form = new \FormCrud\Form("config");
$dados['configForm'] = $form->getForm(1);

$data['data'] = $tpl->getShow('configuracoes', $dados);