<{($action == "link")? "a href='{$link}' target='_blank'" : "div"}
    class='menu-li hover-theme bar-item button z-depth-0 padding'
    data-action="{$action}"
    {if $action == "table"}
        data-relation='{$relation}' data-entity='{$entity}' data-column="{$column}" data-type="{$type}" data-id="{$id}"
    {elseif $action == "form"}
        data-atributo='{$file}' data-lib="{$lib}"
    {elseif $action == "page"}
        data-atributo='{$file}'
    {/if}
    >
    <i class='material-icons left padding-right'>{$icon}</i>
    <span class='left'>{$title}</span>
</{($action == "link")? "a" : "div"}>
