<?php
$tpl = new \Helpers\Template("dashboard");
$routesAll = [];
foreach (\Helpers\Helper::listFolder(PATH_HOME . "vendor/conn") as $item)
    $routesAll[] = $item;

if (DEV)
    $routesAll[] = DOMINIO;

$routes = json_decode(file_get_contents(PATH_HOME . "_config/route.json"), true);

$file = file_get_contents(PATH_HOME . "vendor/conn/link-control/tpl/header.tpl");
$dev = explode(";", explode("const ISDEV = ", $file)[1])[0] === 'true';

?>

<header class="container">
    <h5>
        <b><i class="material-icons left padding-right">settings</i> <span class="left">Configurações</span></b>
    </h5>
</header>

<section class="col padding-32 border-bottom">
    <header class="container col">
        <h2>Desenvolvimento</h2>
    </header>

    <div class="container">

        <div class="left margin-right">
            <label for="dev" class="row">Debugar</label>
            <label class="switch">
                <input type="checkbox" id="dev" data-format="switch" <?= ($dev ? "checked='checked' " : "") ?>
                       class="switchCheck"/>
                <div class="slider"></div>
            </label>
        </div>

        <div class="left padding-xlarge">
            <button id="clear-global" class="btn color-yellow hover-shadow">Limpar Assets Globais</button>
        </div>
    </div>
</section>

<section class="col padding-32 border-bottom">
    <header class="container col">
        <h2>Rotas Aceitas <i class="material-icons" style="cursor: default"
                             title="define quais bibliotecas tem permissão para mostrar conteúdo no sistema (CUIDADO: rotas desconhecidas podem danificar o sistema)">info</i>
        </h2>
    </header>

    <div id="routes-settings">
        <?php
        foreach ($routesAll as $item) {
            $data = [
                "item" => $item,
                "nome" => ucwords(str_replace(["_", "-", "  "], [" ", " ", " "], $item)),
                "value" => in_array($item, $routes),
                "disable" => in_array($item, ["session-control", "dashboard", "link-control", "entity-form"])
            ];
            $tpl->show("checkbox", $data);
        }
        ?>
    </div>
</section>

<section class="col padding-32 border-bottom">
    <header class="container col">
        <h2>Permissões de Usuários <i class="material-icons" style="cursor: default"
                                      title="Controle o que cada usuário pode editar na dashboard.">info</i></h2>
    </header>

    <div class="container">
        <div class="left margin-right">
            <?php
            $dicLogin = \EntityForm\Metadados::getDicionario("login");
            foreach ($dicLogin as $i => $data) {
                if ($data['column'] === "setor") {
                    $setores = $data['allow'];
                    break;
                }
            }

            if (isset($setores)) {
                $entitys = [];
                foreach (\Helpers\Helper::listFolder(PATH_HOME . "entity/cache") as $item) {
                    if ($item !== "info" && $item !== "login_attempt.json" && preg_match('/.json$/i', $item))
                        $entitys[] = str_replace('.json', "", $item);
                }

                $notLogged = file_exists(PATH_HOME . "_config/create_entity_allow_anonimos.json") ? json_decode(file_get_contents(PATH_HOME . "_config/create_entity_allow_anonimos.json"), true) : null;
                $logged = file_exists(PATH_HOME . "_config/create_entity_not_allow_logged.json") ? json_decode(file_get_contents(PATH_HOME . "_config/create_entity_not_allow_logged.json"), true) : null;

                $tpl = new \Helpers\Template("dashboard");
                $tpl->show("list-allow-session", ["value" => 0, "nome" => "Anônimo", "entitys" => $entitys, "allow" => $notLogged]);
                foreach ($setores['values'] as $i => $value)
                    $tpl->show("list-allow-session", ["value" => $value, "nome" => $setores['names'][$i], "entitys" => $entitys, "allow" => $logged[$value] ?? null]);
            }
            ?>
        </div>
    </div>
</section>

<section class="col padding-32 border-bottom">
    <header class="container col">
        <h2>Space DigitalOcean <i class="material-icons" style="cursor: default"
                                  title="Víncula o sistema a uma Space da DigitalOcean, permitindo assim, guardar os arquivos (imagens, vídeos, documentos, etc.) no Space em vez de armazernar no próprio sistema (facilita backups, restaurações e migração além de alivia a banda e armazenamento do servidor).">info</i>
        </h2>
    </header>

    <label class="container col">
        <span>Key Space</span>
        <input type="text" id="spacekey" placeholder="key"
               value="<?= defined('SPACEKEY') && !empty(SPACEKEY) ? SPACEKEY : "" ?>" class="font-large">
    </label>
</section>