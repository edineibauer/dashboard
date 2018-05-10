<header class="container padding-bottom">
    <h5>
        <b><i class="material-icons left padding-right">settings</i> <span class="left">Conexões Sociais</span></b>
    </h5>
</header>

<div class="col s12 m6 padding-small">
    <section class="card padding-8 border-bottom">
        <header class="container col">
            <h2>Instagram</h2>
        </header>

        <div class="col padding-medium font-medium">
            <label class="col padding-small">
                <span class="col">Client ID</span>
                <input type="text" id="instagram_id" value="{$instagram_id}" class="font-xlarge inputConfig">
            </label>
            <label class="col padding-small">
                <span class="col">Client Secret</span>
                <input type="text" id="instagram_secret" value="{$instagram_client}" class="font-xlarge inputConfig">
            </label>
        </div>
    </section>

</div>
<div class="col s12 m6 padding-small">


</div>

<script src="{$home}{$dominio}assets/social_connect.min.js?v={$version}"></script>