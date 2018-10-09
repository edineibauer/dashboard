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

<section class="card padding-8 border-bottom">
    <header class="container col">
        <h2>Reautorar Conteúdo sem Autor
            <i class="material-icons" style="cursor: default"
               title="quando um usuário é excluído, o conteúdo produzido por este usuário fica sem autor, nomeie outra autoridade como autor de conteúdos sem autor.">info</i>
        </h2>
    </header>

    <div class="container">
        <div class="left margin-right">
            <select id="selectReautor">
                {$reautor}
            </select>
        </div>
        <div class="left margin-right">
            <button class="btn color-teal" id="reautorar"><i
                        class="material-icons left padding-right">save</i>Reautorar
            </button>
        </div>
    </div>
</section>

<script src="{$home}{$dominio}assets/desenvolvimento.min.js?v={$version}"></script>