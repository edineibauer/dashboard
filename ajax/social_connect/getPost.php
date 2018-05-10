<?php

$social = trim(strip_tags(filter_input(INPUT_POST, "social", FILTER_DEFAULT)));

if ($social === "instagram") {
    if (defined("INSTAGRAM_ID") && !empty(INSTAGRAM_ID) && defined("INSTAGRAM_SECRET") && !empty(INSTAGRAM_SECRET)) {
        $instagram = new Instagram(array(
            'apiKey' => INSTAGRAM_ID,
            'apiSecret' => INSTAGRAM_SECRET,
            'apiCallback' => HOME . "social_connect/instagram"
        ));

        if (defined("INSTAGRAM_TOKEN") && !empty(INSTAGRAM_TOKEN)) {
            $instagram->setAccessToken(INSTAGRAM_TOKEN);

            \Helpers\Helper::createFolderIfNoExist(PATH_HOME . "uploads");
            \Helpers\Helper::createFolderIfNoExist(PATH_HOME . "uploads/social_connect");
            \Helpers\Helper::createFolderIfNoExist(PATH_HOME . "uploads/social_connect/" . date("Y"));
            \Helpers\Helper::createFolderIfNoExist(PATH_HOME . "uploads/social_connect/" . date("Y") . "/" . date("m"));
            \Helpers\Helper::createFolderIfNoExist(PATH_HOME . "uploads/social_connect/" . date("Y") . "/" . date("m") . "/thumbnail");
            \Helpers\Helper::createFolderIfNoExist(PATH_HOME . "uploads/social_connect/" . date("Y") . "/" . date("m") . "/lowResolution");

            foreach ($instagram->getUserMedia('self', 10)->data as $post) {
                $read = new \ConnCrud\Read();
                $read->exeRead("instagram", "WHERE link = :l", "l={$post->link}");
                if (!$read->getResult()) {
                    $names = [
                        "thumbnail" => pathinfo($post->images->thumbnail->url, PATHINFO_BASENAME),
                        "medium" => pathinfo($post->images->low_resolution->url, PATHINFO_BASENAME),
                        "large" => pathinfo($post->images->standard_resolution->url, PATHINFO_BASENAME)
                    ];

                    $thumbnail = ["name" => $names['thumbnail'], "url" => 'uploads/social_connect/' . date("Y") . '/' . date("m") . "/thumbnail/" . $names['thumbnail'], "extension" => pathinfo($post->images->thumbnail->url, PATHINFO_EXTENSION)];
                    $medium = ["name" => $names['medium'], "url" => 'uploads/social_connect/' . date("Y") . '/' . date("m") . "/lowResolution/" . $names['medium'], "extension" => pathinfo($post->images->low_resolution->url, PATHINFO_EXTENSION)];
                    $large = ["name" => $names['large'], "url" => 'uploads/social_connect/' . date("Y") . '/' . date("m") . "/" . $names['large'], "extension" => pathinfo($post->images->standard_resolution->url, PATHINFO_EXTENSION)];

                    copy($post->images->thumbnail->url, PATH_HOME . $thumbnail['url']);
                    copy($post->images->low_resolution->url, PATH_HOME . $medium['url']);
                    copy($post->images->standard_resolution->url, PATH_HOME . $large['url']);

                    $dados = [
                        "imagem_pequena" => '[{"url": "' . $thumbnail['url'] . '", "name": "' . $thumbnail['name'] . '", "size": 150, "type": "image/' . $thumbnail['extension'] . '"}]',
                        "imagem_media" => '[{"url": "' . $medium['url'] . '", "name": "' . $medium['name'] . '", "size": 320, "type": "image/' . $medium['extension'] . '"}]',
                        "imagem" => '[{"url": "' . $large['url'] . '", "name": "' . $large['name'] . '", "size": 640, "type": "image/' . $large['extension'] . '"}]',
                        "likes" => $post->likes->count,
                        "link" => $post->link,
                        "descricao" => ''
                    ];

                    $create = new \ConnCrud\Create();
                    $create->exeCreate("instagram", $dados);
                }
            }
        } else {
            $data['data'] = $instagram->getLoginUrl();
        }
    }
}
