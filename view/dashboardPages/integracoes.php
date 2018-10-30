<?php
$tpl = new \Helpers\Template("dashboard");
$integ = [
    'integration' => [],
    'home' => HOME,
    'version' => VERSION,
    'vendor' => VENDOR
];
$dados = [];

if(file_exists(PATH_HOME . "_config/config.json"))
    $dados = json_decode(file_get_contents(PATH_HOME . "_config/config.json"), true);

foreach (\Helpers\Helper::listFolder(PATH_HOME . VENDOR) as $item) {
    if(file_exists(PATH_HOME . VENDOR . $item . "/constante")){
        foreach (\Helpers\Helper::listFolder(PATH_HOME . VENDOR . $item . "/constante") as $c) {
            $file = json_decode(file_get_contents(PATH_HOME . VENDOR . $item . "/constante/{$c}"), true);
            foreach ($file['constantes'] as $nome => $column)
                $file['constantes'][$nome]['value'] = $dados[$file['constantes'][$nome]['column']] ?? "";

            $integ['integration'][] = $file;
        }
    }
}

if(!file_exists(PATH_HOME . VENDOR . "dashboard/assets/integracoes.min.js") && file_exists(PATH_HOME . VENDOR . "dashboard/assets/integracoes.js")) {
    $m = new \MatthiasMullie\Minify\JS(PATH_HOME . VENDOR . "dashboard/assets/integracoes.js");
    $minifier->minify(PATH_HOME . VENDOR . "dashboard/assets/integracoes.min.js");
}

$data['data'] = $tpl->getShow('integracoes', $integ);