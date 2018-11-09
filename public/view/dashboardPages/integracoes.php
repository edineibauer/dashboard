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
    if(file_exists(PATH_HOME . VENDOR . $item . "/public/constante")){
        foreach (\Helpers\Helper::listFolder(PATH_HOME . VENDOR . $item . "/public/constante") as $c) {
            $file = json_decode(file_get_contents(PATH_HOME . VENDOR . $item . "/public/constante/{$c}"), true);
            foreach ($file['constantes'] as $nome => $column)
                $file['constantes'][$nome]['value'] = $dados[$file['constantes'][$nome]['column']] ?? "";

            $integ['integration'][] = $file;
        }
    }
}

$data['data'] = $tpl->getShow('integracoes', $integ);