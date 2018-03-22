<?php

if ($_SESSION['userlogin']['setor'] === '1' && $_SESSION['userlogin']['nivel'] === '1') {

    $tpl = new \Helpers\Template("dashboard");
    $routesAll = [];
    foreach (\Helpers\Helper::listFolder(PATH_HOME . "vendor/conn") as $item)
        $routesAll[] = $item;

    if (DEV)
        $routesAll[] = DOMINIO;

    $routes = json_decode(file_get_contents(PATH_HOME . "_config/route.json"), true);

    $file = file_get_contents(PATH_HOME . "_config/config.php");
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
            <h2>Rotas</h2>
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
            <h2>Space DigitalOcean</h2>
        </header>

        <label class="container col">
            <span>Key Space</span>
            <input type="text" id="spacekey" placeholder="key"
                   value="<?= defined('SPACEKEY') && !empty(SPACEKEY) ? SPACEKEY : "" ?>" class="font-large">
        </label>
    </section>

    <section class="col padding-32 border-bottom">
        <header class="container col">
            <h2>Informações do Site</h2>
        </header>
    </section>

    <section class="col padding-32 border-bottom">
        <header class="container col">
            <h2>Email</h2>
        </header>
    </section>

    <section class="col padding-32 border-bottom">
        <header class="container col">
            <h2>Recaptcha</h2>
        </header>
    </section>

    <section class="col padding-32 border-bottom">
        <header class="container col">
            <h2>Cep Aberto</h2>
        </header>
    </section>

    <?php
}