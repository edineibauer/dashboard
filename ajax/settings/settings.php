<?php
$tpl = new \Helpers\Template("dashboard");
$read = new \ConnCrud\Read();
$dados['reautor'] = "";
$dados['dominio'] = DEV && DOMINIO === "dashboard" ? "" : "vendor/conn/dashboard/";
$dados['version'] = VERSION;
$dados['config'] = [
    "sitename" => SITENAME,
    "sitesub" => SITESUB,
    "sitedesc" => SITEDESC,
    "logo" => LOGO,
    "favicon" => FAVICON,
    "protocol" => PROTOCOL,
    "cepaberto" => defined('CEPABERTO') && !empty(CEPABERTO) ? CEPABERTO : "",
    "spacekey" => defined('SPACEKEY') && !empty(SPACEKEY) ? SPACEKEY : "",
    "recaptchasite" => defined('RECAPTCHASITE') && !empty(RECAPTCHASITE) ? RECAPTCHASITE : "",
    "recaptcha" => defined('RECAPTCHA') && !empty(RECAPTCHA) ? RECAPTCHA : "",
    "email" => defined('EMAIL') && !empty(EMAIL) ? EMAIL : "",
    "mailgunkey" => defined('MAILGUNKEY') && !empty(MAILGUNKEY) ? MAILGUNKEY : "",
    "mailgundomain" => defined('MAILGUNDOMAIN') && !empty(MAILGUNDOMAIN) ? MAILGUNDOMAIN : (defined('EMAIL') && !empty(EMAIL) ? explode('@', EMAIL)[1] : ""),
];

$read->exeRead("usuarios", "ORDER BY setor,nivel,nome DESC LIMIT 50");
if ($read->getResult()) {
    foreach ($read->getResult() as $log)
        $dados['reautor'] .= "<option value='{$log['id']}'>{$log['nome']}</option>";
}

$read->exeRead("config", "WHERE id=1");
if (!$read->getResult()) {
    $criarData = [
        "nome_do_site" => defined('SITENAME') && !empty(SITENAME) ? SITENAME : "",
        "subtitulo" => defined('SITESUB') && !empty(SITESUB) ? SITESUB : "",
        "descricao" => defined('SITEDESC') && !empty(SITEDESC) ? SITEDESC : "",
        "protocolo" => defined('PROTOCOL') && !empty(PROTOCOL) && PROTOCOL === "https://" ? 1 : 0,
        "logo" => defined('LOGO') && !empty(LOGO) ? '[{"url": "' . LOGO . '", "name": "", "size": 1078, "type": "image/png"}]' : null,
        "favicon" => defined('FAVICON') && !empty(FAVICON) ? '[{"url": "' . FAVICON . '", "name": "", "size": 1078, "type": "image/png"}]' : null,
    ];
    $d = new \EntityForm\Dicionario("config");
    $d->setData($criarData);
    $d->save();
}

$form = new \FormCrud\Form("config");
$form->setCallback('saveConfigBase');
$dados['configForm'] = $form->getForm(1);

$data['data'] = $tpl->getShow('settings', $dados);