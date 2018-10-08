<?php
$config = json_decode(file_get_contents(PATH_HOME . "_config/config.json"), true);

foreach ($dados as $column => $value) {
    $column = strtolower(trim(strip_tags($column)));
    $column = str_replace(['nome_do_site', 'subtitulo', 'descricao', 'https'], ['sitename', 'sitesub', 'sitedesc', 'ssl'], $column);
    $value = trim(strip_tags($value));
    if (isset($config[$column]))
        $config[$column] = $value;
}

Config::createConfig($config);



if ($field === "PROTOCOL") {
    $www = explode("'", explode("'WWW', '", $file)[1])[0];
    createHtaccess($www, DOMINIO, $value);
} elseif ($field === "WWW") {
    $prot = explode("'", explode("'PROTOCOL', '", $file)[1])[0];
    createHtaccess($value, DOMINIO, $prot);
}

if ((!empty($config['ssl']) && $config['ssl'] !== SSL) || (!empty($config['www']) && $config['www'] !== WWW)) {
    new \Dashboard\UpdateDashboard(['manifest', 'assets', 'lib']);
} elseif ((!empty($config['sitename']) && $config['sitename'] !== SITENAME) || (!empty($config['favicon']) && $config['favicon'] !== FAVICON)) {
    new \Dashboard\UpdateDashboard(['manifest']);
}