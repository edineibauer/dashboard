<?php
$field = strtoupper(trim(strip_tags(filter_input(INPUT_POST, "field", FILTER_DEFAULT))));
$value = trim(strip_tags(filter_input(INPUT_POST, "value", FILTER_DEFAULT)));

function createHtaccess($www = null, $domain = null, $protocol = null)
{
    $dados = "RewriteCond %{HTTP_HOST} ^" . ($www === "www" ? "{$domain}\nRewriteRule ^ {$protocol}www.{$domain}%{REQUEST_URI}" : "www.(.*) [NC]\nRewriteRule ^(.*) {$protocol}%1/$1") . " [L,R=301]";
    $content = str_replace(['{$dados}', '{$home}'], [$dados, HOME], file_get_contents(PATH_HOME . "vendor/conn/config/tpl/htaccess.txt"));

    $fp = fopen(PATH_HOME . ".htaccess", "w+");
    fwrite($fp, $content);
    fclose($fp);
}

if (\Helpers\Check::isJson($value) && preg_match('/url\":/i', $value)) {
    $value = str_replace('\\', '/', json_decode($value, true)[0]['url']);
} elseif ($field === "HTTPS") {
    $field = "PROTOCOL";
    $value = $value === "1" ? "https://" : "http://";
} elseif ($field === "WWW") {
    $value = $value ? "www" : "";
} elseif ($field === "ANALYTICS") {
    if(preg_match("/\'config\', /i", $value))
        $value = explode("'", explode("'config', '", $value)[1])[0];

    if(strlen($value) < 7 && strlen($value) > 20)
        $value = "";
} elseif ($field === "FAVICON") {
    $fav = \WideImage\WideImage::load(PATH_HOME . $value);
    $fav->resize(256, 256)->saveToFile(PATH_HOME . "uploads/site/favicon-256.png");
    $fav->resize(192, 192)->saveToFile(PATH_HOME . "uploads/site/favicon-192.png");
    $fav->resize(152, 152)->saveToFile(PATH_HOME . "uploads/site/favicon-152.png");
    $fav->resize(144, 144)->saveToFile(PATH_HOME . "uploads/site/favicon-144.png");
    $fav->resize(128, 128)->saveToFile(PATH_HOME . "uploads/site/favicon-128.png");
    $fav->resize(90, 90)->saveToFile(PATH_HOME . "uploads/site/favicon-90.png");
    $value = "uploads/site/favicon-90.png";
}

if(!file_exists(PATH_HOME . "manifest.json")) {
    $theme = explode("}", explode(".theme {", file_get_contents(PATH_HOME . "assets" . (DEV ? "Public" : "") . "/theme/theme.css"))[1])[0];
    $themeBack = explode(" ", explode("background-color:", $theme)[1])[0];
    $themeColor = explode(" ", explode("color:", $theme)[1])[0];
    $content = str_replace(['{$sitename}', '{$favicon}', '{$theme}', '{$themeColor}'], [SITENAME, FAVICON, $themeBack, $themeColor], file_get_contents(PATH_HOME . "vendor/conn/config/tpl/manifest.txt"));

    $fp = fopen(PATH_HOME . "manifest.json", "w+");
    fwrite($fp, $content);
    fclose($fp);
}

$value = trim(strip_tags(str_replace("'", '"', $value)));

$file = file_get_contents(PATH_HOME . "_config/config.php");
if (preg_match("/\'{$field}\',/i", $file)) {
    $valueOld = explode("'", explode("('{$field}', '", $file)[1])[0];
    $file = str_replace("'{$field}', '{$valueOld}'", "'{$field}', '{$value}'", $file);
} else {
    $file = str_replace("<?php", "<?php\ndefine('{$field}', '{$value}');", $file);
}

$f = fopen(PATH_HOME . "_config/config.php", "w+");
fwrite($f, $file);
fclose($f);

if ($field === "PROTOCOL") {
    $www = explode("'", explode("'WWW', '", $file)[1])[0];
    createHtaccess($www, DOMINIO, $value);
} elseif ($field === "WWW") {
    $prot = explode("'", explode("'PROTOCOL', '", $file)[1])[0];
    createHtaccess($value, DOMINIO, $prot);
}