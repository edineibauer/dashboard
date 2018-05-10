<?php
$data = [
    'instagram_id' => defined("INSTAGRAM_ID") ? INSTAGRAM_ID : "",
    'instagram_secret' => defined("INSTAGRAM_SECRET") ? INSTAGRAM_SECRET : "",
];

$tpl->show("social_connect", $data);