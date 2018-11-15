<?php

use Helpers\Helper;

//copia entidades
if (file_exists(PATH_HOME . "entity/cache")) {
    foreach (Helper::listFolder(PATH_HOME . "entity/cache") as $entity) {
        if (preg_match('/\.json$/i', $entity)) {

            //Para cada Entidade
            $isMyEntity = true;
            foreach (Helper::listFolder(PATH_HOME . VENDOR) as $lib) {
                if ($isMyEntity && file_exists(PATH_HOME . VENDOR . "{$lib}/public/entity/cache/{$entity}"))
                    $isMyEntity = false;
            }

            if ($isMyEntity) {
                copy(PATH_HOME . "entity/cache/{$entity}", PATH_HOME . "public/entity/cache/{$entity}");
                copy(PATH_HOME . "entity/cache/info/{$entity}", PATH_HOME . "public/entity/cache/info/{$entity}");
            }
        }
    }
}

//copia configurações de menu
if (file_exists(PATH_HOME . "_config/menu_not_show.json")) {
    if (file_exists(PATH_HOME . "public/dash/-menu.json")) {
        $f = json_decode(file_get_contents(PATH_HOME . "public/dash/-menu.json"), true);
        $n = json_decode(file_get_contents(PATH_HOME . "_config/menu_not_show.json"), true);

        foreach ($n as $setor => $libs) {
            foreach ($libs as $lib) {
                if (!isset($f[$setor]) || !in_array($lib, $f[$setor]))
                    $f[$setor][] = $lib;
            }
        }

        $o = fopen(PATH_HOME . "public/dash/-menu.json", "w+");
        fwrite($o, json_encode($f));
        fclose($o);

    } else {
        copy(PATH_HOME . "_config/menu_not_show.json", PATH_HOME . "public/dash/-menu.json");
    }
}

//copia configurações de permissão de entidades
if (file_exists(PATH_HOME . "_config/entity_not_show.json")) {
    if (file_exists(PATH_HOME . "public/entity/-entity.json")) {
        $f = json_decode(file_get_contents(PATH_HOME . "public/entity/-entity.json"), true);
        $n = json_decode(file_get_contents(PATH_HOME . "_config/entity_not_show.json"), true);

        foreach ($n as $setor => $libs) {
            foreach ($libs as $lib) {
                if (!isset($f[$setor]) || !in_array($lib, $f[$setor]))
                    $f[$setor][] = $lib;
            }
        }

        $o = fopen(PATH_HOME . "public/entity/-entity.json", "w+");
        fwrite($o, json_encode($f));
        fclose($o);

    } else {
        copy(PATH_HOME . "_config/entity_not_show.json", PATH_HOME . "public/entity/-entity.json");
    }
}



