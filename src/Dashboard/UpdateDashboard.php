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
    private $devLibrary;

    /**
     * UpdateDashboard constructor.
     * @param mixed $force
     */
    public function __construct($force = null)
    {
        $this->devLibrary = "http://uebster.com/library";
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
                $version = $this->updateVersionSystem();
                $this->updateVersion($version);
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

        return $newVersion;
    }

    private function checkAdminExist()
    {
        $read = new Read();
        $read->exeRead(PRE . "usuarios", "WHERE setor = 1 ORDER BY id ASC LIMIT 1");
        if (!$read->getResult())
            Entity::add("usuarios", ["nome" => "Admin", "nome_usuario" => "admin", "setor" => 1, "email" => (!defined('EMAIL') ? "contato@ontab.com.br" : EMAIL), "password" => "mudar"]);
    }

    private function updateVersion(string $version = VERSION)
    {
        $f = fopen(PATH_HOME . "_config/updates/version.txt", "w+");
        fwrite($f, file_get_contents(PATH_HOME . "composer.lock"));
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

        foreach (Helper::listFolder(PATH_HOME . VENDOR) as $lib) {
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

    private function createMinifyAssetsLib()
    {
        foreach (Helper::listFolder(PATH_HOME . VENDOR) as $lib) {
            foreach (Helper::listFolder(PATH_HOME . VENDOR . $lib . "/assets") as $file) {
                $ext = pathinfo($file, PATHINFO_EXTENSION);
                $name = pathinfo($file, PATHINFO_FILENAME);

                if (in_array($ext, ['css', 'js']) && !preg_match('/\.min$/i', $name) && !file_exists(PATH_HOME . VENDOR . $lib . "/assets/{$name}.min.{$ext}")) {
                    if ($ext === "js")
                        $minifier = new Minify\JS(file_get_contents(PATH_HOME . VENDOR . $lib . "/assets/{$name}.js"));
                    else
                        $minifier = new Minify\CSS(file_get_contents(PATH_HOME . VENDOR . $lib . "/assets/{$name}.css"));

                    $minifier->minify(PATH_HOME . VENDOR . $lib . "/assets/{$name}.min.{$ext}");
                }
            }
        }

        $f = [];
        if(file_exists(PATH_HOME . "_config/param.json"))
            $f = json_decode(file_get_contents(PATH_HOME . "_config/param.json"), true);

        $this->createCoreJs($f['js'], 'core');
        $this->createCoreCss($f['css'], 'core');
        $this->createCoreFont($f['font'], $f['icon'], 'fonts');

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


    /**
     * @param array $jsList
     * @param string $name
     */
    private function createCoreJs(array $jsList, string $name = "core")
    {
        if (!file_exists(PATH_HOME . "assetsPublic/{$name}.min.js")) {
            $minifier = new Minify\JS("");
            foreach ($jsList as $js)
                $minifier->add(PATH_HOME . $this->checkAssetsExist($js, "js"));

            $minifier->minify(PATH_HOME . "assetsPublic/{$name}.min.js");
        }
    }

    /**
     * @param array $cssList
     * @param string $name
     */
    private function createCoreCss(array $cssList, string $name = "core")
    {
        if (!file_exists(PATH_HOME . "assetsPublic/{$name}.min.css")) {
            $minifier = new Minify\CSS("");
            $minifier->setMaxImportSize(30);
            foreach ($cssList as $css)
                $minifier->add(PATH_HOME . $this->checkAssetsExist($css, "css"));

            $minifier->minify(PATH_HOME . "assetsPublic/{$name}.min.css");
        }
    }

    /**
     * @param $fontList
     * @param null $iconList
     * @param string $name
     */
    private function createCoreFont($fontList, $iconList = null, string $name = 'fonts')
    {
        if (!file_exists(PATH_HOME . "assetsPublic/{$name}.min.css")) {
            $fonts = "";
            if ($fontList) {
                foreach ($fontList as $item)
                    $fonts .= $this->getFontIcon($item, "font");
            }
            if ($iconList) {
                foreach ($iconList as $item)
                    $fonts .= $this->getFontIcon($item, "icon");
            }

            $m = new Minify\CSS($fonts);
            $m->minify(PATH_HOME . "assetsPublic/{$name}.min.css");
        }
    }

    /**
     * Verifica se uma lib existe no sistema, se não existir, baixa do server
     *
     * @param string $lib
     * @param string $extensao
     * @return string
     */
    private function checkAssetsExist(string $lib, string $extensao): string
    {
        if (!file_exists("assetsPublic/{$lib}/{$lib}.min.{$extensao}")) {
            $this->createFolderAssetsLibraries("assetsPublic/{$lib}/{$lib}.min.{$extensao}");
            if (!Helper::isOnline("{$this->devLibrary}/{$lib}/{$lib}" . ".{$extensao}"))
                return "";

            if ($extensao === 'js')
                $mini = new Minify\JS(file_get_contents("{$this->devLibrary}/{$lib}/{$lib}" . ".{$extensao}"));
            else
                $mini = new Minify\CSS(file_get_contents("{$this->devLibrary}/{$lib}/{$lib}" . ".{$extensao}"));

            $mini->minify(PATH_HOME . "assetsPublic/{$lib}/{$lib}.min.{$extensao}");
        }

        return "assetsPublic/{$lib}/{$lib}.min.{$extensao}";
    }

    /**
     * @param string $file
     */
    private function createFolderAssetsLibraries(string $file)
    {
        $link = PATH_HOME;
        $split = explode('/', $file);
        foreach ($split as $i => $peca) {
            if ($i < count($split) - 1) {
                $link .= ($i > 0 ? "/" : "") . $peca;
                Helper::createFolderIfNoExist($link);
            }
        }
    }

    /**
     * @param string $item
     * @param string $tipo
     * @return string
     */
    private function getFontIcon(string $item, string $tipo): string
    {
        $data = "";
        $urlOnline = $tipo === "font" ? "https://fonts.googleapis.com/css?family=" . ucfirst($item) . ":100,300,400,700" : "https://fonts.googleapis.com/icon?family=" . ucfirst($item) . "+Icons";
        if (Helper::isOnline($urlOnline)) {
            $data = file_get_contents($urlOnline);
            foreach (explode('url(', $data) as $i => $u) {
                if ($i > 0) {
                    $url = explode(')', $u)[0];
                    if (!file_exists(PATH_HOME . "assetsPublic/fonts/" . pathinfo($url, PATHINFO_BASENAME))) {
                        if (Helper::isOnline($url)) {
                            Helper::createFolderIfNoExist(PATH_HOME . "assetsPublic/fonts");
                            $f = fopen(PATH_HOME . "assetsPublic/fonts/" . pathinfo($url, PATHINFO_BASENAME), "w+");
                            fwrite($f, file_get_contents($url));
                            fclose($f);
                            $data = str_replace($url, HOME . "assetsPublic/fonts/" . pathinfo($url, PATHINFO_BASENAME), $data);
                        } else {
                            $before = "@font-face" . explode("@font-face", $u[$i - 1])[1] . "url(";
                            $after = explode("}", $u)[0];
                            $data = str_replace($before . $after, "", $data);
                        }
                    } else {
                        $data = str_replace($url, HOME . "assetsPublic/fonts/" . pathinfo($url, PATHINFO_BASENAME), $data);
                    }
                }
            }
        }
        return $data;
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
            $shellNewVersion = $shell + 0.01;
            $data = explode("'", explode("swDataConn-", $worker)[1])[0];
            $dataNewVersion = $data + 0.01;
        } else {
            $shell = "1.0.0";
            $shellNewVersion = "1.0.1";
            $data = "1.0.0";
            $dataNewVersion = "1.0.1";
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