<header class="container">
    <h5>
        <b><i class="material-icons left padding-right">settings</i> <span class="left">Configurações</span></b>
    </h5>
</header>

<section class="col padding-32 border-bottom">
    <header class="container col">
        <h2>Reautorar Conteúdo sem Autor <i class="material-icons" style="cursor: default"
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

<script src="{$home}{$dominio}assets/settings.min.js?v={$version}"></script>