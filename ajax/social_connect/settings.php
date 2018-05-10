<?php
use MetzWeb\Instagram\Instagram;

$dados = [
    'instagram_id' => defined("INSTAGRAM_ID") ? INSTAGRAM_ID : "",
    'instagram_secret' => defined("INSTAGRAM_SECRET") ? INSTAGRAM_SECRET : "",
    'btnAllow' => ""
];

if(defined("INSTAGRAM_ID") && !empty(INSTAGRAM_ID) && defined("INSTAGRAM_SECRET") && !empty(INSTAGRAM_SECRET)) {
    $instagram = new Instagram(array(
        'apiKey'      => INSTAGRAM_ID,
        'apiSecret'   => INSTAGRAM_SECRET,
        'apiCallback' => HOME . "social_connect/instagram"
    ));

    $dados['btnAllow'] .= "<a class='btn-large theme margin-bottom button-connect-social' id='btn-instagram-connect' href='{$instagram->getLoginUrl()}'>Conectar Site ao Instagram</a>";
}

$tpl = new \Helpers\Template("dashboard");
$data['data'] .= $tpl->getShow("social_connect", $dados);