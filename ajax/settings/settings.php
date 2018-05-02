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
    "cepaberto" => CEPABERTO,
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

$form = new \FormCrud\Form("config");
$form->setCallback('saveConfigBase');
$dados['configForm'] = $form->getForm(1);

$data['data'] = $tpl->getShow('settings', $dados);