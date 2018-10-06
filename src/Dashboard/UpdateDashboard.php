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
        if(file_exists(PATH_HOME . "composer.lock")) {
            $keyVersion = json_decode(file_get_contents(PATH_HOME . "composer.lock"), true)['content-hash'];
            if (file_exists(PATH_HOME . "_config/updates/version.txt")) {
                $old = file_get_contents(PATH_HOME . "_config/updates/version.txt");
                if ($old !== $keyVersion || $force) {
                    $version = $this->updateVersionNumber();
                    $this->updateVersion($keyVersion, $version);
                }

            } else {
                Helper::createFolderIfNoExist(PATH_HOME . "_config/updates");
                $this->updateVersion($keyVersion, VERSION);
            }
        }
    }

    private function updateVersionNumber()
    {
        $conf = file_get_contents(PATH_HOME . "_config/config.php");
        $newVersion = VERSION + 0.01;
        $conf = str_replace("'VERSION', '" . VERSION . "')", "'VERSION', '{$newVersion}')", $conf);
        $f = fopen(PATH_HOME . "_config/config.php", "w");
        fwrite($f, $conf);
        fclose($f);

        return $newVersion;
    }

    private function checkAdminExist()
    {
        $read = new Read();
        $read->exeRead(PRE . "usuarios", "WHERE setor = 1 ORDER BY id ASC LIMIT 1");
        if (!$read->getResult())
            Entity::add("usuarios", ["nome" => "Admin", "nome_usuario" => "admin", "setor" => 1, "email" => (!defined('EMAIL') ? "contato@ontab.com.br" : EMAIL), "password" => "mudar"]);
    }

    private function updateVersion(string $versionKey, string $version = VERSION)
    {
        $f = fopen(PATH_HOME . "_config/updates/version.txt", "w+");
        fwrite($f, $versionKey);
        fclose($f);

        $this->updateDependenciesEntity();
        $this->checkAdminExist();
        $this->updateAssets();
        $this->createMinifyAssetsLib();
        $this->updateServiceWorker($version);
        $this->result = true;
    }

    private function updateAssets()
    {
        //Remove only core Assets
        unlink(PATH_HOME . "assetsPublic/core.min.js");
        unlink(PATH_HOME . "assetsPublic/core.min.css");
        unlink(PATH_HOME . "assetsPublic/fonts.min.css");

        //Remove todos os Assets Publics
        /* foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator("assetsPublic", \RecursiveDirectoryIterator::SKIP_DOTS),
             \RecursiveIteratorIterator::CHILD_FIRST) as $file) {
             if (!in_array($file->getFileName(), ["theme.min.css", "theme", "theme.css", "theme-recovery.min.css", "theme-recovery.css"])) {
                 if ($file->isDir())
                     rmdir($file->getRealPath());
                 elseif ($file->getFileName())
                     unlink($file->getRealPath());
             }
         }*/
    }

    private function createMinifyAssetsLib()
    {
        //Para cada arquivo css e js presente nas bibliotecas dentro da pasta assets, minifica quando não existe
        foreach (Helper::listFolder(PATH_HOME . VENDOR) as $lib) {
            foreach (Helper::listFolder(PATH_HOME . VENDOR . $lib . "/assets") as $file) {
                $ext = pathinfo($file, PATHINFO_EXTENSION);
                $name = pathinfo($file, PATHINFO_FILENAME);
                if (in_array($ext, ['css', 'js']) && !file_exists(PATH_HOME . VENDOR . $lib . "/assets/{$name}.min.{$ext}") && !preg_match('/\w+\.min\.(css|js)$/i', $name)) {
                    if ($ext === "js")
                        $minifier = new Minify\JS(file_get_contents(PATH_HOME . VENDOR . $lib . "/assets/{$name}.js"));
                    else
                        $minifier = new Minify\CSS(file_get_contents(PATH_HOME . VENDOR . $lib . "/assets/{$name}.css"));
                    $minifier->minify(PATH_HOME . VENDOR . $lib . "/assets/{$name}.min.{$ext}");
                }
            }
        }
    }

    private function generateInfo(array $metadados): array
    {
        $data = [
            "identifier" => 0, "title" => null, "link" => null, "status" => null, "date" => null, "datetime" => null, "valor" => null, "email" => null, "tel" => null, "cpf" => null, "cnpj" => null, "cep" => null, "time" => null, "week" => null, "month" => null, "year" => null,
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

        //importa entidades ausentes para o sistema
        foreach (Helper::listFolder(PATH_HOME . VENDOR) as $lib) {
            if (file_exists(PATH_HOME . VENDOR . "{$lib}/entity/cache")) {
                foreach (Helper::listFolder(PATH_HOME . VENDOR . "{$lib}/entity/cache") as $file) {
                    if (!file_exists(PATH_HOME . "entity/cache/{$file}") && preg_match('/\w+\.json$/i', $file)) {
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

                        new EntityCreateEntityDatabase(str_replace('.json', '', $file), []);
                    }
                }
            }
        }
    }

    private function checkCacheContent($path, $listShell, $listData, string $version)
    {
        //templates mustache
        if (file_exists(PATH_HOME . "{$path}tpl")) {
            foreach (Helper::listFolder(PATH_HOME . "{$path}tpl") as $tpl) {
                if (preg_match('/\.mst$/i', $tpl))
                    $listShell[] = HOME . "{$path}tpl/{$tpl}";
            }
        }

        //assets
        if (file_exists(PATH_HOME . "{$path}assets")) {
            foreach (Helper::listFolder(PATH_HOME . "{$path}assets") as $asset) {
                if (!preg_match('/\./i', $asset)) {
                    foreach (Helper::listFolder(PATH_HOME . "{$path}assets/{$asset}") as $a) {
                        if (!preg_match('/\.(js|css)$/i', $a) || preg_match('/\.min\.(js|css)$/i', $a))
                            $listShell[] = HOME . "{$path}assets/{$asset}/{$a}" . (preg_match('/\.(js|css)$/i', $a) ? "?v=" . $version : "");
                    }
                } elseif (!preg_match('/\.(js|css)$/i', $asset) || preg_match('/\.min\.(js|css)$/i', $asset)) {
                    $listShell[] = HOME . "{$path}assets/{$asset}" . (preg_match('/\.(js|css)$/i', $asset) ? "?v=" . $version : "");
                }
            }
        }

        //pages
        if (file_exists(PATH_HOME . "{$path}view")) {
            foreach (Helper::listFolder(PATH_HOME . "{$path}view") as $view) {
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

    /**
     * @param string $version
     */
    private function updateServiceWorker(string $version)
    {
        $listShell = [HOME . "assetsPublic/core.min.js?v=" . $version, HOME . "assetsPublic/core.min.css?v=" . $version];
        $listData = [substr(HOME, 0, -1)];

        if (!empty(LOGO)) {
            $listShell[] = HOME . LOGO;
            $listShell[] = HOME . 'image/' . LOGO . "&h=100";
        }

        if (!empty(FAVICON)) {
            $listShell[] = HOME . FAVICON;
            $listShell[] = HOME . 'image/' . FAVICON . "&h=100";
        }

        if (file_exists(PATH_HOME . "assetsPublic/fonts.min.css"))
            $listShell[] = HOME . "assetsPublic/fonts.min.css?v=" . $version;

        foreach (Helper::listFolder(PATH_HOME . "assetsPublic/fonts") as $font) {
            if (preg_match('/\.(ttf|woff|woff2)$/', $font))
                $listShell[] = HOME . "assetsPublic/fonts/{$font}";
        }

        //Cache Content Link Control
        list($listShell, $listData) = $this->checkCacheContent(VENDOR . "link-control/", $listShell, $listData, $version);
        list($listShell, $listData) = $this->checkCacheContent(VENDOR . "session-control/", $listShell, $listData, $version);
        list($listShell, $listData) = $this->checkCacheContent("public/", $listShell, $listData, $version);

        if (file_exists(PATH_HOME . "service-worker.js")) {
            $worker = file_get_contents(PATH_HOME . "service-worker.js");
            $shell = explode("'", explode("swShellConn-", $worker)[1])[0];
            $data = explode("'", explode("swDataConn-", $worker)[1])[0];
        } else {
            $shell = "1.0.0";
            $data = "1.0.0";
        }

        $f = fopen(PATH_HOME . "service-worker.js", "w");
        $file = file_get_contents(PATH_HOME . VENDOR . "config/tpl/service-worker.txt");
        $content = str_replace("var filesToCache = [];", "var filesToCache = " . json_encode($listShell, JSON_UNESCAPED_SLASHES) . ";", $file);
        $content = str_replace("var filesToCacheAfter = [];", "var filesToCacheAfter = " . json_encode($listData, JSON_UNESCAPED_SLASHES) . ";", $content);
        $content = str_replace(["swShellConn-{$shell}'", "swDataConn-{$data}'"], ["swShellConn-{$version}'", "swDataConn-{$version}'"], $content);

        fwrite($f, $content);
        fclose($f);
    }

    private function checkSource($valores)
    {
        $type = [];
        $data = [
            "image" => ["png", "jpg", "jpeg", "gif", "bmp", "tif", "tiff", "psd", "svg"],
            "video" => ["mp4", "avi", "mkv", "mpeg", "flv", "wmv", "mov", "rmvb", "vob", "3gp", "mpg"],
            "audio" => ["mp3", "aac", "ogg", "wma", "mid", "alac", "flac", "wav", "pcm", "aiff", "ac3"],
            "document" => ["txt", "doc", "docx", "dot", "dotx", "dotm", "ppt", "pptx", "pps", "potm", "potx", "pdf", "xls", "xlsx", "xltx", "rtf"],
            "compact" => ["rar", "zip", "tar", "7z"],
            "denveloper" => ["html", "css", "scss", "js", "tpl", "json", "xml", "md", "sql", "dll"]
        ];

        foreach ($data as $tipo => $dados) {
            if (count(array_intersect($dados, $valores)) > 0)
                $type[] = $tipo;
        }

        if (count($type) > 1) {
            if (count(array_intersect(["document", "compact", "denveloper"], $type)) === 0 && count(array_intersect(["image", "video", "audio"], $type)) > 1)
                return "multimidia";
            else if (count(array_intersect(["document", "compact", "denveloper"], $type)) > 1 && count(array_intersect(["image", "video", "audio"], $type)) === 0)
                return "arquivo";
            else
                return "source";
        } else {
            return $type[0];
        }
    }
}