<{($action == "link")? "a href='{$link}' target='_blank'" : "div"}
    class='menu-li menu-dashboard-lista hover-theme bar-item button z-depth-0 padding'
    data-action="{$action}"
    {if $action == "table"}
        data-entity='{$entity}'
    {elseif $action == "form"}
        data-atributo='{$file}' data-lib="{$lib}"
    {elseif $action == "page"}
        data-atributo='{$file}'
    {/if}
    >
    <i class='material-icons left padding-right'>{$icon}</i>
    <span class='left'>{$title}</span>
</{($action == "link")? "a" : "div"}>
