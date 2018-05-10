<?php
use MetzWeb\Instagram\Instagram;

if(defined("INSTAGRAM_ID") && !empty(INSTAGRAM_ID) && defined("INSTAGRAM_SECRET") && !empty(INSTAGRAM_SECRET)) {
    $instagram = new Instagram(array(
        'apiKey'      => INSTAGRAM_ID,
        'apiSecret'   => INSTAGRAM_SECRET,
        'apiCallback' => HOME . "social_connect/instagram"
    ));

    $code = explode('?code=', $_SERVER['REQUEST_URI'])[1];
    $data = $instagram->getOAuthToken($code, false);
    if(!empty($data->access_token)) {
        $field = "INSTAGRAM_TOKEN";
        $value = $data->access_token;

        $file = file_get_contents(PATH_HOME . "_config/config.php");
        if (preg_match("/\'{$field}\',/i", $file)) {
            $valueOld = explode("'", explode("('{$field}', '", $file)[1])[0];
            $file = str_replace("'{$field}', '{$valueOld}'", "'{$field}', '{$value}'", $file);
        } else {
            $file = str_replace("<?php", "<?php\ndefine('{$field}', '{$value}');", $file);
        }

        $f = fopen(PATH_HOME . "_config/config.php", "w+");
        fwrite($f, $file);
        fclose($f);

        $titulo = "Conectado ao Instagram";
        $read = new \ConnCrud\Read();
        $read->exeRead("dashboard_note", "WHERE titulo = :t", "t={$titulo}");
        if(!$read->getResult()) {
            $create = new \ConnCrud\Create();
            $create->exeCreate("dashboard_note", ['titulo' => $titulo, "descricao" => "Sua conexão com o instagram foi realizada com sucesso!", "status" => 1, "data" => date("Y-m-d H:i:s")]);
        }

        header("Location:" . HOME . "dashboard");
    } else {
        echo "Erro com o code retornado do login permission";
    }
}
