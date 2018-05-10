<?php
use MetzWeb\Instagram\Instagram;

$id = filter_input(INPUT_POST, "id", FILTER_DEFAULT);

if(defined("INSTAGRAM_ID") && !empty(INSTAGRAM_ID) && defined("INSTAGRAM_SECRET") && !empty(INSTAGRAM_SECRET)) {
    $instagram = new Instagram(array(
        'apiKey' => INSTAGRAM_ID,
        'apiSecret' => INSTAGRAM_SECRET,
        'apiCallback' => HOME . "social_connect/instagram"
    ));

    $data['data'] = "<a class='btn-large theme margin-bottom button-connect-social' id='{$id}' href='{$instagram->getLoginUrl()}'>Conectar Site ao Instagram</a>";
}