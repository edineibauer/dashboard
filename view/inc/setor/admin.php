<?php

if ($_SESSION['userlogin']['nivel'] === '1') {
    echo "<a href='" . HOME . "entidades' target='_blank' class='btn-entity hover-theme bar-item button z-depth-0 padding'><i class='material-icons left padding-right'>accessibility</i><span class='left'>Gerenciar Entidades</span></a>";
}

foreach (\Helpers\Helper::listFolder(PATH_HOME . "entity/cache") as $item) {
    if ($item !== "login_attempt.json" && $item !== "info" && preg_match('/\.json$/i', $item)) {
        if($item !== "login.json" || $_SESSION['userlogin']['nivel'] < 3)
        echo "<button class='btn-entity hover-theme bar-item button z-depth-0 padding' data-entity='" . str_replace('.json', '', $item) . "'><i class='material-icons left padding-right'>account_balance_wallet</i><span class='left'>" . ucwords(trim(str_replace(['.json', '-', '_'], ['', ' ', ' '], $item))) . "</span></button>";

    }
}