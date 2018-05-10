<?php
$dados = [
    'instagram_id' => defined("INSTAGRAM_ID") ? INSTAGRAM_ID : "",
    'instagram_secret' => defined("INSTAGRAM_SECRET") ? INSTAGRAM_SECRET : "",
    'instagram_token' => defined("INSTAGRAM_TOKEN") ? INSTAGRAM_TOKEN : ""
];

$tpl = new \Helpers\Template("dashboard");
$data['data'] .= $tpl->getShow("social_connect", $dados);