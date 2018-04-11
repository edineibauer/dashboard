<header class="container">
    <h5>
        <b><i class="material-icons left padding-right">settings_input_component</i> <span class="left">Desenvolvimento</span></b>
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
                <input type="checkbox" id="dev" data-format="switch" class="switchCheck"
                        {($dev) ? "checked='checked' " : " "}/>
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
        {$routesAll}
    </div>
</section>

<section class="col padding-32 border-bottom">
    <header class="container col">
        <h2>Permissões de Usuários <i class="material-icons" style="cursor: default"
                                      title="Controle o que cada usuário pode editar na dashboard.">info</i></h2>
    </header>

    <div class="container">
        <div class="left margin-right">
            {$permissao}
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
               value="{$spacekey}" class="font-large">
    </label>
</section>
<script src="{$home}{$dominio}assets/dev.min.js?v={$version}"></script>