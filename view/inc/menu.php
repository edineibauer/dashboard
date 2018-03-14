<?php
$restrict = empty($restrict) ? [] : $restrict;
$restrict = array_map(function ($v) {return $v . ".json";}, $restrict);
foreach (\Helpers\Helper::listFolder(PATH_HOME . "entity/cache") as $item) {
    if (!in_array($item, $restrict) && preg_match('/\.json$/i', $item) && $item !== "login_attempt.json" && $item !== "info")
        echo "<button class='btn-entity hover-theme bar-item button z-depth-0 padding' data-entity='" . str_replace('.json', '', $item) . "'><i class='material-icons left padding-right'>account_balance_wallet</i><span class='left'>" . ucwords(trim(str_replace(['.json', '-', '_'], ['', ' ', ' '], $item))) . "</span></button>";
}