<header class="container">
    <h5>
        <b><i class="material-icons left padding-right">settings_ethernet</i> <span class="left">Desenvolvimento</span></b>
    </h5>
</header>

<section class="col padding-32 border-bottom">
    <header class="container col">
        <h2>Desenvolvimento</h2>
    </header>

    <div class="container">
        <div class="left padding-xlarge">
            <button id="clear-cache" class="btn color-orange hover-shadow">Atualizar Sistema</button>
        </div>
        <div class="left padding-xlarge">
            <button id="clear-global" class="btn color-yellow hover-shadow">Atualizar Assets Globais</button>
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

<script src="{$home}{$dominio}assets/desenvolvimento.min.js?v={$version}"></script>