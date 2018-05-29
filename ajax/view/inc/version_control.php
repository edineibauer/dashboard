<?php

use Helpers\Helper;
use \ConnCrud\Read;
use \Entity\Entity;

function checkAdminExist()
{
    $read = new Read();
    $read->exeRead(PRE . "usuarios", "WHERE setor = 1 ORDER BY id ASC LIMIT 1");
    if (!$read->getResult())
        Entity::add("usuarios", ["nome" => "Admin", "nome_usuario" => "admin", "setor" => 1, "email" => (!defined('EMAIL') ? "contato@ontab.com.br" : EMAIL), "password" => "mudar"]);
}

function updateVersionTxt()
{
    $f = fopen(PATH_HOME . "_config/updates/version.txt", "w+");
    fwrite($f, file_get_contents(PATH_HOME . "composer.lock"));
    fclose($f);
    updateDependenciesEntity();
    checkAdminExist();

    //Recarrega cache de assets excluindo tudo
    $dir = PATH_HOME . (DEV ? "assetsPublic" : "assets");
    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST) as $file) {
        if (!in_array($file->getFileName(), ["theme.css", "theme.min.css", "theme"])) {
            if ($file->isDir())
                rmdir($file->getRealPath());
            elseif ($file->getFileName())
                unlink($file->getRealPath());
        }
    }

    $data['response'] = 3;
    $data['data'] = HOME . "dashboard";
}

function generateInfo(array $metadados): array
{
    $data = [
        "identifier" => $this->id, "title" => null, "link" => null, "status" => null, "date" => null, "datetime" => null, "valor" => null, "email" => null, "tel" => null, "cpf" => null, "cnpj" => null, "cep" => null, "time" => null, "week" => null, "month" => null, "year" => null,
        "required" => null, "unique" => null, "publisher" => null, "constant" => null, "extend" => null, "extend_mult" => null, "list" => null, "list_mult" => null, "selecao" => null, "selecao_mult" => null,
        "source" => [
            "image" => null,
            "audio" => null,
            "video" => null,
            "multimidia" => null,
            "compact" => null,
            "document" => null,
            "denveloper" => null,
            "arquivo" => null,
            "source" => null
        ]
    ];

    foreach ($metadados as $i => $dados) {
        if (in_array($dados['key'], ["unique", "extend", "extend_mult", "list", "list_mult", "selecao", "selecao_mult"]))
            $data[$dados['key']][] = $i;

        if (in_array($dados['format'], ["title", "link", "status", "date", "datetime", "valor", "email", "tel", "cpf", "cnpj", "cep", "time", "week", "month", "year"]))
            $data[$dados['format']] = $i;

        if ($dados['key'] === "publisher")
            $data["publisher"] = $i;

        if ($dados['key'] === "source" || $dados['key'] === "sources")
            $data['source'][$this->checkSource($dados['allow']['values'])][] = $i;

        if ($dados['default'] === false)
            $data['required'][] = $i;

        if (!$dados['update'])
            $data["constant"][] = $i;
    }

    return $data;
}

function updateDependenciesEntity()
{
    Helper::createFolderIfNoExist(PATH_HOME . "entity");
    Helper::createFolderIfNoExist(PATH_HOME . "entity/cache");
    Helper::createFolderIfNoExist(PATH_HOME . "entity/cache/info");

    foreach (\Helpers\Helper::listFolder(PATH_HOME . "vendor/conn") as $lib) {
        if (file_exists(PATH_HOME . "vendor/conn/{$lib}/entity/cache")) {
            foreach (Helper::listFolder(PATH_HOME . "vendor/conn/{$lib}/entity/cache") as $file) {
                if ($file !== "info" && preg_match('/\w+\.json$/i', $file) && !file_exists(PATH_HOME . "entity/cache/{$file}")) {
                    copy(PATH_HOME . "vendor/conn/{$lib}/entity/cache/{$file}", PATH_HOME . "entity/cache/{$file}");
                    if (file_exists(PATH_HOME . "vendor/conn/{$lib}/entity/cache/info/{$file}")) {
                        copy(PATH_HOME . "vendor/conn/{$lib}/entity/cache/info/{$file}", PATH_HOME . "entity/cache/info/{$file}");

                    } else {
                        //cria info
                        $data = generateInfo(\EntityForm\Metadados::getDicionario(PATH_HOME . "vendor/conn/{$lib}/entity/cache/{$file}"));
                        $fp = fopen(PATH_HOME . "entity/cache/info/" . $file, "w");
                        fwrite($fp, json_encode($data));
                        fclose($fp);
                    }
                }
            }
        }
    }

    foreach (Helper::listFolder(PATH_HOME . "entity/cache") as $entity) {
        if ($entity !== "info" && preg_match('/\w+\.json$/i', $entity))
            new EntityForm\EntityCreateEntityDatabase(str_replace('.json', '', $entity), []);
    }
}

function updateServiceWorker()
{
    $listShell = [];
    $listData = [];
    $assets = (DEV ? "assetsPublic/" : "assets/");
    if (!empty(LOGO))
        $listShell[] = HOME . LOGO;
    if (!empty(LOGO))
        $listShell[] = HOME . FAVICON;

    //base assets public
    $listShell[] = HOME . $assets . "linkControl.min.js?v=" . VERSION;
    $listShell[] = HOME . $assets . "linkControl.min.css?v=" . VERSION;
    if(file_exists(PATH_HOME . $assets . "fonts.min.css"))
        $listShell[] = HOME . $assets . "fonts.min.css?v=" . VERSION;

    function checkCacheContent($path, $listShell, $listData) {
        //templates
        if (file_exists(PATH_HOME . "{$path}tplFront")) {
            foreach (\Helpers\Helper::listFolder(PATH_HOME . "{$path}tplFront") as $tpl)
                $listShell[] = HOME . "{$path}tplFront/{$tpl}";
        }

        //assets
        if (file_exists(PATH_HOME . "{$path}assets")) {
            foreach (\Helpers\Helper::listFolder(PATH_HOME . "{$path}assets") as $asset) {
                if (!preg_match('/\./i', $asset)) {
                    foreach (\Helpers\Helper::listFolder(PATH_HOME . "{$path}assets/{$asset}") as $a) {
                        if (preg_match('/\./i', $a) && (!preg_match('/\.(js|css)$/i', $a) || preg_match('/\.min\.(js|css)$/i', $a)))
                            $listShell[] = HOME . "{$path}assets/{$asset}/{$a}" . (preg_match('/\.(js|css)$/i', $asset) ? "?v=" . VERSION : "");
                    }
                } elseif (!preg_match('/\.(js|css)$/i', $asset) || preg_match('/\.min\.(js|css)$/i', $asset)) {
                    $listShell[] = HOME . "{$path}assets/{$asset}" . (preg_match('/\.(js|css)$/i', $asset) ? "?v=" . VERSION : "");
                }
            }
        }

        //pages
        if (file_exists(PATH_HOME . "{$path}ajax/view")) {
            foreach (\Helpers\Helper::listFolder(PATH_HOME . "{$path}ajax/view") as $view) {
                if (preg_match('/\.php$/i', $view)) {
                    $listData[] = HOME . "request/get/view/" . str_replace('.php', '', $view);
                    $listData[] = HOME . str_replace(['.php', 'index'], '', $view);
                }
            }
        }
        if (file_exists(PATH_HOME . "{$path}ajax/dobra")) {
            foreach (\Helpers\Helper::listFolder(PATH_HOME . "{$path}ajax/dobra") as $view) {
                if (preg_match('/\.php$/i', $view))
                    $listData[] = HOME . "request/get/dobra/" . str_replace('.php', '', $view);
            }
        }

        return [$listShell, $listData];
    }

    //theme lib
    foreach (\Helpers\Helper::listFolder(PATH_HOME . "vendor/conn") as $lib) {
        if($lib === "link-control" || file_exists(PATH_HOME . "vendor/conn/{$lib}/view/index.php")) {
            list($listShell, $listData) = checkCacheContent("vendor/conn/{$lib}/", $listShell, $listData);
        }
    }

    if (DEV)
        list($listShell, $listData) = checkCacheContent("", $listShell, $listData);

    $f = fopen(PATH_HOME . "service-worker.js", "w+");
    $file = file_get_contents(PATH_HOME . "vendor/conn/config/tpl/service-worker.txt");
    $content = str_replace("var filesToCache = [];", "var filesToCache = " . json_encode($listShell, JSON_UNESCAPED_SLASHES) . ";", $file);
    $content = str_replace("var filesToCacheAfter = [];", "var filesToCacheAfter = " . json_encode($listData, JSON_UNESCAPED_SLASHES) . ";", $content);
    fwrite($f, $content);
    fclose($f);
}

updateServiceWorker();
if (file_exists(PATH_HOME . "_config/updates/version.txt")) {
    $old = file_get_contents(PATH_HOME . "_config/updates/version.txt");
    $actual = file_get_contents(PATH_HOME . "composer.lock");
    if ($old !== $actual) {
        $conf = file_get_contents(PATH_HOME . "_config/config.php");
        $version = explode("')", explode("'VERSION', '", $conf)[1])[0];
        $newVersion = $version + 0.01;
        $conf = str_replace("'VERSION', '{$version}')", "'VERSION', '{$newVersion}')", $conf);
        $f = fopen(PATH_HOME . "_config/config.php", "w");
        fwrite($f, $conf);
        fclose($f);
        updateVersionTxt();
    }

} else {
    Helper::createFolderIfNoExist(PATH_HOME . "_config/updates");
    updateVersionTxt();
}