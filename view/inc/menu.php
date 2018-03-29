<?php
$notAllowCreateLogged = file_exists(PATH_HOME . "_config/create_entity_not_allow_logged.json") ? json_decode(file_get_contents(PATH_HOME . "_config/create_entity_not_allow_logged.json"), true) : null;
foreach (\Helpers\Helper::listFolder(PATH_HOME . "entity/cache") as $item) {
    $entity = str_replace('.json', '', $item);
    if ((empty($notAllowCreateLogged[$_SESSION['userlogin']['setor']]) || !in_array($entity, $notAllowCreateLogged[$_SESSION['userlogin']['setor']])) && preg_match('/\.json$/i', $item) && $item !== "login_attempt.json" && $item !== "info")
        echo "<button class='btn-entity hover-theme bar-item button z-depth-0 padding' data-entity='" . $entity . "'><i class='material-icons left padding-right'>account_balance_wallet</i><span class='left'>" . ucwords(trim(str_replace(['-', '_'], [' ', ' '], $entity))) . "</span></button>";
}