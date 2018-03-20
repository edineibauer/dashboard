<div class="left container">
    <label class="col card {($disable) ? "disabled" : ""}">
        <input type="checkbox" class="left margin-left" id="routes-setting"
               value="{$item}" {($value) ? "checked='checked' " : "" }
                {($disable) ? "disabled='disabled'" : ""}
        />
        <div class="font-medium left padding-8 padding-right pointer">{$nome}</div>
    </label>
</div>
