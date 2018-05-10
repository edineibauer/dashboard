<?php
$link = filter_input(INPUT_POST, "link", FILTER_DEFAULT);
$timeout = 15;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $link);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; WOW64; rv:52.0) Gecko/20100101 Firefox/52.0");
curl_setopt($ch, CURLOPT_COOKIEJAR, tempnam("/tmp", "CURLCOOKIE"));
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_ENCODING, "gzip, deflate, br");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_AUTOREFERER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);    # required for https urls
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
curl_setopt($ch, CURLOPT_MAXREDIRS, 10);

$content = curl_exec($ch);
$response = curl_getinfo($ch);
curl_close($ch);

$data['data'] = $content;