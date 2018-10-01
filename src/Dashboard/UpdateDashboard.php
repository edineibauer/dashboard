<?php

namespace Dashboard;

use EntityForm\EntityCreateEntityDatabase;
use Helpers\Helper;
use \ConnCrud\Read;
use \Entity\Entity;
use MatthiasMullie\Minify;

class UpdateDashboard
{
    private $result;

    /**
     * UpdateDashboard constructor.
     * @param mixed $force
     */
    public function __construct($force = null)
    {
        $this->start($force);
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    private function start($force)
    {
        if (file_exists(PATH_HOME . "_config/updates/version.txt")) {
            $old = file_get_contents(PATH_HOME . "_config/updates/version.txt");
            $actual = file_get_contents(PATH_HOME . "composer.lock");
            if ($old !== $actual || $force) {
                $this->updateVersionSystem();
                $this->updateVersion();
            }

        } else {
            Helper::createFolderIfNoExist(PATH_HOME . "_config/updates");
            $this->updateVersion();
        }
    }

    private function updateVersionSystem()
    {
        $conf = file_get_contents(PATH_HOME . "_config/config.php");
        $version = explode("')", explode("'VERSION', '", $conf)[1])[0];
        $newVersion = $version + 0.01;
        $conf = str_replace("'VERSION', '{$version}')", "'VERSION', '{$newVersion}')", $conf);
        $f = fopen(PATH_HOME . "_config/config.php", "w");
        fwrite($f, $conf);
        fclose($f);
    }

    private function checkAdminExist()
    {
        $read = new Read();
        $read->exeRead(PRE . "usuarios", "WHERE setor = 1 ORDER BY id ASC LIMIT 1");
        if (!$read->getResult())
            Entity::add("usuarios", ["nome" => "Admin", "nome_usuario" => "admin", "setor" => 1, "email" => (!defined('EMAIL') ? "contato@ontab.com.br" : EMAIL), "password" => "mudar"]);
    }

    private function updateVersion()
    {
        $f = fopen(PATH_HOME . "_config/updates/version.txt", "w+");
        fwrite($f, file_get_contents(PATH_HOME . "composer.lock"));
        fclose($f);

        $this->updateDependenciesEntity();
        $this->checkAdminExist();
        $this->updateAssets();
        $this->createMinifyAssetsLib();
        $this->updateServiceWorker();
        $this->result = true;
    }

    private function updateAssets()
    {
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator("assetsPublic", \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST) as $file) {
            if (!in_array($file->getFileName(), ["theme.min.css", "theme", "theme.css", "theme-recovery.min.css", "theme-recovery.css"])) {
                if ($file->isDir())
                    rmdir($file->getRealPath());
                elseif ($file->getFileName())
                    unlink($file->getRealPath());
            }
        }
    }

    private function generateInfo(array $metadados): array
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

    private function updateDependenciesEntity()
    {
        Helper::createFolderIfNoExist(PATH_HOME . "entity");
        Helper::createFolderIfNoExist(PATH_HOME . "entity/cache");
        Helper::createFolderIfNoExist(PATH_HOME . "entity/cache/info");

        foreach (\Helpers\Helper::listFolder(PATH_HOME . VENDOR) as $lib) {
            if (file_exists(PATH_HOME . VENDOR . "{$lib}/entity/cache")) {
                foreach (Helper::listFolder(PATH_HOME . VENDOR . "{$lib}/entity/cache") as $file) {
                    if ($file !== "info" && preg_match('/\w+\.json$/i', $file) && !file_exists(PATH_HOME . "entity/cache/{$file}")) {
                        copy(PATH_HOME . VENDOR . "{$lib}/entity/cache/{$file}", PATH_HOME . "entity/cache/{$file}");
                        if (file_exists(PATH_HOME . VENDOR . "{$lib}/entity/cache/info/{$file}")) {
                            copy(PATH_HOME . VENDOR . "{$lib}/entity/cache/info/{$file}", PATH_HOME . "entity/cache/info/{$file}");

                        } else {
                            //cria info
                            $data = $this->generateInfo(\EntityForm\Metadados::getDicionario(PATH_HOME . VENDOR . "{$lib}/entity/cache/{$file}"));
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
                new EntityCreateEntityDatabase(str_replace('.json', '', $entity), []);
        }
    }

    private function checkCacheContent($path, $listShell, $listData)
    {
        //templates mustache
        if (file_exists(PATH_HOME . "{$path}tpl")) {
            foreach (\Helpers\Helper::listFolder(PATH_HOME . "{$path}tpl") as $tpl) {
                if (preg_match('/\.mst$/i', $tpl))
                    $listShell[] = HOME . "{$path}tpl/{$tpl}";
            }
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
        if (file_exists(PATH_HOME . "{$path}view")) {
            foreach (\Helpers\Helper::listFolder(PATH_HOME . "{$path}view") as $view) {
                if (preg_match('/\.php$/i', $view)) {
                    $listData[] = HOME . str_replace(['.php', 'index'], '', $view);
                    $listData[] = HOME . "get/" . str_replace('.php', '', $view);
                    if (file_exists(PATH_HOME . "{$path}view/data/{$view}"))
                        $listData[] = HOME . "get/data/" . str_replace('.php', '', $view);
                }
            }
        }

        return [$listShell, $listData];
    }

    private function createMinifyAssetsLib()
    {
        foreach (Helper::listFolder(PATH_HOME . VENDOR) as $lib) {
            if (file_exists(PATH_HOME . VENDOR . "{$lib}/assets")) {
                foreach (Helper::listFolder(PATH_HOME . VENDOR . "{$lib}/assets") as $assets) {
                    $tipo = pathinfo($assets, PATHINFO_EXTENSION);
                    if (($tipo === "css" || $tipo === "js") && !preg_match('/\.min\.(css|js)$/i', $assets)) {
                        $name = pathinfo($assets, PATHINFO_FILENAME);
                        if ($tipo === "css")
                            $mini = new Minify\CSS(PATH_HOME . VENDOR . "{$lib}/assets/{$assets}");
                        else
                            $mini = new Minify\JS(PATH_HOME . VENDOR . "{$lib}/assets/{$assets}");

                        $mini->minify(PATH_HOME . VENDOR . "{$lib}/assets/{$name}.min.{$tipo}");
                    }
                }
            }
        }

        if (file_exists(PATH_HOME . "public/assets")) {
            foreach (Helper::listFolder(PATH_HOME . "public/assets") as $assets) {
                $tipo = pathinfo($assets, PATHINFO_EXTENSION);
                if (($tipo === "css" || $tipo === "js") && !preg_match('/\.min\.(css|js)$/i', $assets)) {
                    $name = pathinfo($assets, PATHINFO_FILENAME);
                    if ($tipo === "css")
                        $mini = new Minify\CSS(PATH_HOME . "public/assets/{$assets}");
                    else
                        $mini = new Minify\JS(PATH_HOME . "public/assets/{$assets}");

                    $mini->minify(PATH_HOME . "public/assets/{$name}.min.{$tipo}");
                }
            }
        }
    }

    private function updateServiceWorker()
    {
        $listShell = [];
        $listData = [];
        if (!empty(LOGO))
            $listShell[] = HOME . LOGO;
        if (!empty(LOGO))
            $listShell[] = HOME . FAVICON;

        //base assets public
        $listShell[] = HOME . "assetsPublic/core.min.js?v=" . VERSION;
        $listShell[] = HOME . "assetsPublic/core.min.css?v=" . VERSION;
        if (file_exists(PATH_HOME . "assetsPublic/fonts.min.css"))
            $listShell[] = HOME . "assetsPublic/fonts.min.css?v=" . VERSION;

        foreach (Helper::listFolder(PATH_HOME . "assetsPublic/fonts") as $font) {
            if (preg_match('/\.(ttf|woff|woff2)$/', $font))
                $listShell[] = HOME . "assetsPublic/fonts/{$font}";
        }

        //Cache Content Link Control
        list($listShell, $listData) = $this->checkCacheContent(VENDOR . "link-control/", $listShell, $listData);

        //Cache Content public
        list($listShell, $listData) = $this->checkCacheContent("public/", $listShell, $listData);

        if (file_exists(PATH_HOME . "service-worker.js")) {
            $worker = file_get_contents(PATH_HOME . "service-worker.js");
            $shell = explode("'", explode("swShellConn-", $worker)[1])[0];
            $shellNewVersion = $shell + 0.01;
            $data = explode("'", explode("swDataConn-", $worker)[1])[0];
            $dataNewVersion = $data + 0.01;
        }

        $f = fopen(PATH_HOME . "service-worker.js", "w");
        $file = file_get_contents(PATH_HOME . VENDOR . "config/tpl/service-worker.txt");
        $content = str_replace("var filesToCache = [];", "var filesToCache = " . json_encode($listShell, JSON_UNESCAPED_SLASHES) . ";", $file);
        $content = str_replace("var filesToCacheAfter = [];", "var filesToCacheAfter = " . json_encode($listData, JSON_UNESCAPED_SLASHES) . ";", $content);

        if (isset($shellNewVersion))
            $content = str_replace(["swShellConn-{$shell}'", "swDataConn-{$data}'"], ["swShellConn-{$shellNewVersion}'", "swDataConn-{$dataNewVersion}'"], $content);

        fwrite($f, $content);
        fclose($f);
    }
}