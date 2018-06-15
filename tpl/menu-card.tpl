<{($action == "link")? "a href='{$link}' target='_blank'" : "div"} class="col padding-medium">
    <div class="col align-center border padding-medium color-grey-light opacity radius pointer hover-opacity-off menu-li"
         data-action="{$action}"
         {if $action == "table"}
             data-relation='{$relation}' data-entity='{$entity}' data-column="{$column}" data-type="{$type}" data-id="{$id}"
         {elseif $action == "form"}
             data-atributo='{$file}' data-lib="{$lib}"
         {elseif $action == "page"}
             data-atributo='{$file}'
         {/if}
    >
        <i class="font-xxxlarge material-icons">{$icon}</i>
        <span class="font-large col">{$title}</span>
    </div>
</{($action == "link")? "a" : "div"}>