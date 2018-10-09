<?php
$tpl = new \Helpers\Template("dashboard");
$read = new \ConnCrud\Read();
$dados['dominio'] = DOMINIO === "dashboard" ? "" : VENDOR . "dashboard/";
$dados['version'] = VERSION;
$dados['config'] = [
    "sitename" => SITENAME,
    "sitesub" => SITESUB,
    "sitedesc" => SITEDESC,
    "logo" => LOGO,
    "analytics" =>  defined('ANALYTICS') && !empty(ANALYTICS) ? ANALYTICS : "",
    "favicon" => FAVICON,
    "protocol" => PROTOCOL,
    "cepaberto" => defined('CEPABERTO') && !empty(CEPABERTO) ? CEPABERTO : "",
    "spacekey" => defined('SPACEKEY') && !empty(SPACEKEY) ? SPACEKEY : "",
    "recaptchasite" => defined('RECAPTCHASITE') && !empty(RECAPTCHASITE) ? RECAPTCHASITE : "",
    "recaptcha" => defined('RECAPTCHA') && !empty(RECAPTCHA) ? RECAPTCHA : "",
    "email" => defined('EMAIL') && !empty(EMAIL) ? EMAIL : "",
    "emailkey" => defined('EMAILKEY') && !empty(EMAILKEY) ? EMAILKEY : "",
];

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
    $d = new \EntityForm\Dicionario("config");
    $d->setData($criarData);
    $d->save();
}

$form = new \FormCrud\Form("config");
$dados['configForm'] = $form->getForm(1);

$data['data'] = $tpl->getShow('settings', $dados);