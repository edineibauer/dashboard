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

    header("Location:" . HOME . "dashboard");
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
    $list = [HOME, HOME . "index.php"];
    $listAfter = [];
    if (!empty(LOGO))
        $list[] = HOME . LOGO;
    if (!empty(LOGO))
        $list[] = HOME . FAVICON;

    //base assets public
    $baseAssets = DEV ? "assetsPublic/" : "assets/";
    foreach (\Helpers\Helper::listFolder(PATH_HOME . $baseAssets) as $asset) {
        if (file_exists(PATH_HOME . $baseAssets . $asset . "/{$asset}.min.js"))
            $list[] = HOME . $baseAssets . $asset . "/{$asset}.min.js";
        elseif (file_exists(PATH_HOME . $baseAssets . $asset . "/{$asset}.js"))
            $list[] = HOME . $baseAssets . $asset . "/{$asset}.js";

        if (file_exists(PATH_HOME . $baseAssets . $asset . "/{$asset}.min.css"))
            $list[] = HOME . $baseAssets . $asset . "/{$asset}.min.css";
        elseif (file_exists(PATH_HOME . $baseAssets . $asset . "/{$asset}.css"))
            $list[] = HOME . $baseAssets . $asset . "/{$asset}.css";
    }

    //templates front
    foreach (\Helpers\Helper::listFolder(PATH_HOME . "vendor/conn/link-control/tplFront") as $tpl)
        $list[] = HOME . "vendor/conn/link-control/tplFront/{$tpl}";


    //assets theme lib
    foreach (\Helpers\Helper::listFolder(PATH_HOME . "vendor/conn") as $lib) {
        if (file_exists(PATH_HOME . "vendor/conn/{$lib}/ajax/view/index.php")) {
            //todas as páginas inc
            foreach (\Helpers\Helper::listFolder(PATH_HOME . "vendor/conn/{$lib}/ajax/inc") as $view) {
                if (preg_match('/[\.php|\.html]$/i', $view))
                    $list[] = HOME . "vendor/conn/{$lib}/ajax/inc/{$view}";
            }

            //todas as páginas principais
            foreach (\Helpers\Helper::listFolder(PATH_HOME . "vendor/conn/{$lib}/ajax/view") as $view) {
                if (preg_match('/[\.php|\.html]$/i', $view))
                    $list[] = HOME . "vendor/conn/{$lib}/ajax/view/{$view}";
            }

            //todas as páginas dobras
            foreach (\Helpers\Helper::listFolder(PATH_HOME . "vendor/conn/{$lib}/ajax/dobra") as $view) {
                if (preg_match('/[\.php|\.html]$/i', $view))
                    $list[] = HOME . "vendor/conn/{$lib}/ajax/view/{$view}";
            }

            //assets do tema
            foreach (\Helpers\Helper::listFolder(PATH_HOME . "vendor/conn/{$lib}/assets") as $asset)
                $list[] = HOME . "vendor/conn/{$lib}/assets/{$asset}";

            //param do tema
            foreach (\Helpers\Helper::listFolder(PATH_HOME . "vendor/conn/{$lib}/param") as $param)
                $list[] = HOME . "vendor/conn/{$lib}/param/{$param}";
        }
    }

    //pages
    foreach (\Helpers\Helper::listFolder(PATH_HOME . "vendor/conn") as $lib) {
        if (file_exists(PATH_HOME . "vendor/conn/{$lib}/ajax/view")) {
            foreach (\Helpers\Helper::listFolder(PATH_HOME . "vendor/conn/{$lib}/ajax/view") as $view) {
                if (preg_match('/\.php$/i', $view)) {
                    $listAfter[] = HOME . "request/get/{$lib}/view/" . str_replace('.php', '', $view);
                    $listAfter[] = HOME . str_replace('.php', '', $view);
                }
            }
        }
        if (file_exists(PATH_HOME . "vendor/conn/{$lib}/ajax/dobra")) {
            foreach (\Helpers\Helper::listFolder(PATH_HOME . "vendor/conn/{$lib}/ajax/dobra") as $view) {
                if (preg_match('/\.php$/i', $view))
                    $listAfter[] = HOME . "request/get/{$lib}/dobra/" . str_replace('.php', '', $view);
            }
        }
        if (file_exists(PATH_HOME . "vendor/conn/{$lib}/assets")) {
            foreach (\Helpers\Helper::listFolder(PATH_HOME . "vendor/conn/{$lib}/assets") as $view)
                $listAfter[] = HOME . "vendor/conn/{$lib}/assets/{$view}";
        }

        if (DEV) {
            if (file_exists(PATH_HOME . "ajax/view")) {
                foreach (\Helpers\Helper::listFolder(PATH_HOME . "ajax/view") as $view) {
                    if (preg_match('/\.php$/i', $view))
                        $listAfter[] = HOME . "request/get/" . DOMINIO . "/view/" . str_replace('.php', '', $view);
                }
            }
            if (file_exists(PATH_HOME . "ajax/dobra")) {
                foreach (\Helpers\Helper::listFolder(PATH_HOME . "ajax/dobra") as $view) {
                    if (preg_match('/\.php$/i', $view))
                        $listAfter[] = HOME . "request/get/" . DOMINIO . "/dobra/" . str_replace('.php', '', $view);
                }
            }
            if (file_exists(PATH_HOME . "assets")) {
                foreach (\Helpers\Helper::listFolder(PATH_HOME . "assets") as $view)
                    $listAfter[] = HOME . "assets/{$view}";
            }
        }
    }

    $f = fopen(PATH_HOME . "service-worker.js", "w+");
    $file = file_get_contents(PATH_HOME . "vendor/conn/config/tpl/service-worker.txt");
    $content = str_replace("var filesToCache = [];", "var filesToCache = " . json_encode($list, JSON_UNESCAPED_SLASHES) . ";", $file);
    $content = str_replace("var filesToCacheAfter = [];", "var filesToCacheAfter = " . json_encode($listAfter, JSON_UNESCAPED_SLASHES) . ";", $content);
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