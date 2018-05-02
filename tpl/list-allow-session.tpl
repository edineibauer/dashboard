<div class="col s12 m4 l3 padding-right">
    <div class="col card">
        <header class="col container">
            <h1 class="font-large">{$nome}</h1>
            {if $nome !== "Anônimo"}<b class="color-text-red col upper">NÃO Permitir</b>{else}<b class="color-text-green col upper">Permitir</b>{/if}
        </header>
        <p class="col container">Menu / Acesso à:</p>
        {foreach item=entity key=i from=$entitys}
            <label class="col">
                <input type="checkbox" class="left margin-left allow-menu-session"
                       value="{$entity}" rel="{$value}" {if $allowMenu != null && in_array($entity, $allowMenu)}checked='checked' {/if}/>
                <input type="checkbox" class="left margin-left allow-session"
                       value="{$entity}" rel="{$value}" {if $allow != null && in_array($entity, $allow)}checked='checked' {/if}/>
                <div class="font-medium left padding-8 padding-right pointer">{$entity|replace:"_":" "}</div>
            </label>
        {/foreach}
    </div>
</div>
