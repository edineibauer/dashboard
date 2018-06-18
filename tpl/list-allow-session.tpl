<div class="col s12 m4 l3 padding-right">
    <div class="col card">
        <header class="col container">
            <h1 class="font-large">{$nome}</h1>
            <b class="color-text-red col upper">NÃO Permitir</b>
        </header>
        <p class="col container">Menu / Acesso à:</p>
        {foreach item=entity key=i from=$entitys}
            <div class="col">
                <input type="checkbox" class="left margin-left allow-menu-session"
                       value="{$entity}" rel="{$value}"
                       {if $allowMenu != null && in_array($entity, $allowMenu)}checked='checked' {/if}/>
                <label>
                    <input type="checkbox" class="left margin-left allow-session"
                           value="{$entity}" rel="{$value}"
                           {if $allow != null && in_array($entity, $allow)}checked='checked' {/if}/>
                    <div class="font-medium left padding-8 padding-right pointer">{$entity|replace:"_":" "}</div>
                </label>
            </div>
        {/foreach}
    </div>
</div>
