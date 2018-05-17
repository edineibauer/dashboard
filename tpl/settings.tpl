<header class="container padding-bottom">
    <h5>
        <b><i class="material-icons left padding-right">settings</i> <span class="left">Configurações</span></b>
    </h5>
</header>

<div class="col s12 m6 padding-small">
    <section class="card padding-8 border-bottom">
        <header class="container col">
            <h2>Informações do Site</h2>
        </header>

        <div class="col padding-medium font-medium">
            {$configForm}
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
</div>
<div class="col s12 m6 padding-small">

    <section class="card padding-8 border-bottom">
        <header class="container col padding-8">
            <a href="https://analytics.google.com/analytics/web" target="_blank" class="font-xxlarge" style="text-decoration: none">Analytics</a>
        </header>

        <div class="col padding-medium font-medium">
            <label class="col padding-small">
                <span class="col">Código de Acompanhamento</span>
                <textarea id="analytics" rows="4" class="font-small inputConfig">{$config['analytics']}</textarea>
            </label>
        </div>
    </section>

    <section class="card padding-8 border-bottom">
        <header class="container col padding-8">
            <a href="https://www.sparkpost.com/" target="_blank" class="font-xxlarge" style="text-decoration: none">SparkPost</a>
        </header>

        <div class="col padding-medium font-medium">
            <label class="col padding-small">
                <span class="col">Email</span>
                <input type="text" id="email" value="{$config['email']}" class="font-xlarge inputConfig">
            </label>
            <label class="col padding-small">
                <span class="col">Key</span>
                <input type="text" id="emailkey" value="{$config['emailkey']}" class="font-xlarge inputConfig">
            </label>
        </div>
    </section>

    <section class="card padding-8 border-bottom">
        <header class="container col">
            <h2>Recaptcha</h2>
        </header>
        <div class="col padding-medium font-medium">
            <label class="col padding-small">
                <span class="col">Site</span>
                <input type="text" id="recaptchasite" value="{$config['recaptchasite']}" class="font-xlarge inputConfig">
            </label>
            <label class="col padding-small">
                <span class="col">Key Recaptcha</span>
                <input type="text" id="recaptcha" value="{$config['recaptcha']}" class="font-xlarge inputConfig">
            </label>
        </div>
    </section>

    <section class="card padding-8 border-bottom">
        <header class="container col">
            <h2>Cep Aberto</h2>
        </header>
        <div class="col padding-medium font-medium">
            <label class="col padding-small">
                <span class="col">Key</span>
                <input type="text" id="cepaberto" value="{$config['cepaberto']}" class="font-xlarge inputConfig">
            </label>
        </div>
    </section>

    <section class="card padding-8 border-bottom">
        <header class="container col">
            <h2>Space DigitalOcean
                <i class="material-icons" style="cursor: default"
                   title="Víncula o sistema a uma Space da DigitalOcean, permitindo assim, guardar os arquivos (imagens, vídeos, documentos, etc.) no Space em vez de armazernar no próprio sistema (facilita backups, restaurações e migração além de alivia a banda e armazenamento do servidor).">info</i>
            </h2>
        </header>

        <div class="col padding-medium font-medium">
            <label class="col padding-small">
                <span>Key Space</span>
                <input type="text" id="spacekey" placeholder="key" value="{$config['spacekey']}"
                       class="font-xlarge inputConfig">
            </label>
        </div>
    </section>

</div>

<script src="{$home}{$dominio}assets/settings.min.js?v={$version}"></script>