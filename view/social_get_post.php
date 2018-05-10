<?php
use MetzWeb\Instagram\Instagram;

if($link->getUrl()[1] === "instagram") {
    if (defined("INSTAGRAM_ID") && !empty(INSTAGRAM_ID) && defined("INSTAGRAM_SECRET") && !empty(INSTAGRAM_SECRET)) {
        $instagram = new Instagram(array(
            'apiKey' => INSTAGRAM_ID,
            'apiSecret' => INSTAGRAM_SECRET,
            'apiCallback' => HOME . "social_connect/instagram"
        ));

        if(defined("INSTAGRAM_TOKEN") && !empty(INSTAGRAM_TOKEN)) {
            $instagram->setAccessToken(INSTAGRAM_TOKEN);
            var_dump($instagram->getUserMedia('self', 10)->data);
        } else {
            header("Location:" . $instagram->getLoginUrl());
        }
    }
}
